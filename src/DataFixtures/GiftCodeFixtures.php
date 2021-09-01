<?php /** @noinspection PhpParamsInspection */

namespace App\DataFixtures;

use App\Entity\GiftCode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class GiftCodeFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $giftCode = new GiftCode();
            $giftCode
                ->setCode($this->faker->unique()->realText(10));
            $this->addReference("giftCode_$i", $giftCode);

            $manager->persist($giftCode);
        }

        $manager->flush();
    }


    public function getDependencies(): array
    {
        return [
            FactoryFixtures::class
        ];
    }

}
