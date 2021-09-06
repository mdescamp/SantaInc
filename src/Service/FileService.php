<?php
declare(strict_types=1);

namespace App\Service;

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private Kernel $kernel;
    private string $waitingDir;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->waitingDir = $kernel->getProjectDir() . '/public/waiting';
    }

    public function save(UploadedFile $uploadedFile, int $factory): void
    {
        if (!is_dir($this->waitingDir) && !mkdir($this->waitingDir) && !is_dir($this->waitingDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $this->waitingDir));
        }

        rename(
            $uploadedFile->getPathname(),
            $this->waitingDir . '/' . $factory . '-' . uniqid('', true)
        );

        //TODO use a cron job
        $application = new Application($this->kernel);
        $input = new ArrayInput(['command' => 'file:import']);
        $output = new BufferedOutput();
        $application->doRun($input, $output);
    }
}
