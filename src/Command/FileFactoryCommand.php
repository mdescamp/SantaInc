<?php

namespace App\Command;

use App\Entity\Factory;
use App\Entity\Gift;
use App\Entity\GiftCode;
use App\Entity\Receiver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'file:import',
    description: 'Take data from uploaded files',
)]
class FileFactoryCommand extends Command
{
    private EntityManagerInterface $em;
    private KernelInterface $kernel;

    public const GIFT_UUID = 0;
    public const GIFT_CODE = 1;
    public const GIFT_DESC = 2;
    public const GIFT_PRIC = 3;

    public const RECEI_UUID = 4;
    public const RECEI_FIRS = 5;
    public const RECEI_LAST = 6;
    public const RECEI_COUN = 7;

    private array $firstLineExpected = [
        0 => "gift_uuid",
        1 => "gift_code",
        2 => "gift_description",
        3 => "gift_price",
        4 => "receiver_uuid",
        5 => "receiver_first_name",
        6 => "receiver_last_name",
        7 => "receiver_country_code",
    ];
    private array $errors = [];


    public function __construct(KernelInterface $kernel, EntityManagerInterface $em)
    {
        $this->kernel = $kernel;
        $this->em = $em;
        parent::__construct();
    }

    private function createDir(string ...$dir): void
    {
        foreach ($dir as $d) {
            if (!is_dir($d) && !mkdir($d) && !is_dir($d)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $d));
            }
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $waitingDir = $this->kernel->getProjectDir() . '/public/waiting';
        $processedDir = $this->kernel->getProjectDir() . '/public/processed';
        $errorDir = $this->kernel->getProjectDir() . '/public/error';
        $countColumns = \count($this->firstLineExpected);

        $this->createDir($processedDir, $errorDir);
        if (is_dir($waitingDir) && $dh = opendir($waitingDir)) {
            while (($file = readdir($dh)) !== false) {
                $filePath = $waitingDir . '/' . $file;
                if (is_file($filePath) && ($handle = fopen($filePath, 'rb')) !== false) {
                    $i = 0;
                    $last = [];
                    /** @var array $data */
                    while (($data = fgetcsv($handle)) !== false && \is_array($data) && empty($this->errors)) {
                        if ($i === 0) {
                            $this->getErrors($data, $file, $filePath);
                        } elseif (\count($last) > $countColumns) {
                            $this->errors[$filePath] = "File '$file' is not correctly formatted";
                        } elseif (\count($last) === $countColumns) {
                            $data = $last;
                            $last = [];
                        } elseif (\count($data) !== $countColumns) {
                            $this->removeLineBreak($last, $data);
                        } else {
                            $this->hydrateDb($file, $data);
                        }
                        $i = 1;
                    }
                    if (empty($this->errors)) {
                        $this->em->flush();
                    } else {
                        foreach ($this->errors as $error) {
                            $io->error($error);
                        }
                        rename($filePath, $errorDir . '/' . $file);
                    }
                }
            }
        }

        $io->success('Success !!');

        return Command::SUCCESS;
    }

    protected function getErrors(array $data, string $file, string $filePath): void
    {
        foreach ($this->firstLineExpected as $key => $value) {
            if ($data[$key] !== $value) {
                $this->errors[$filePath] = "File '$file' is not correctly formatted";
                break;
            }
        }
    }

    protected function removeLineBreak(array &$last, array &$data): void
    {
        if (!empty($last)) {
            $last[array_key_last($last)] .= "\n" . $data[0];
            array_shift($data);
        }
        $last = array_merge($last, $data);
    }

    protected function hydrateDb(string $file, array $data): void
    {
        $giftCode = $this->em->getRepository(GiftCode::class)->findOneBy(['code' => $data[self::GIFT_CODE]]);
        if ($giftCode === null) {
            $giftCode = new GiftCode();
            $giftCode->setCode($data[self::GIFT_CODE]);
            $this->em->persist($giftCode);
        }

        $gift = new Gift();
        $gift
            ->setFactory($this->em->find(Factory::class, explode('-', $file)[0]))
            ->setUuid($data[self::GIFT_UUID])
            ->setCode($giftCode)
            ->setDescription(nl2br(htmlspecialchars($data[self::GIFT_DESC])))
            ->setPrice($data[self::GIFT_PRIC]);
        $this->em->persist($gift);

        $receiver = new Receiver();
        $receiver
            ->setUuid($data[self::RECEI_UUID])
            ->setLastName($data[self::RECEI_LAST])
            ->setFirstName($data[self::RECEI_FIRS])
            ->setCountry($data[self::RECEI_COUN])
            ->addGift($gift);
        $this->em->persist($receiver);
    }
}
