<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Gift;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class GiftFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $gift = new Gift();
            $gift
                ->setDescription($this->faker->realText($this->faker->numberBetween(10, 1000)))
                ->setPrice($this->faker->randomFloat(2, 0, 500))
                ->setUuid($this->faker->uuid)
                ->setCode($this->getReference('giftCode_' . $this->faker->numberBetween(1, 10)))
                ->setFactory($this->getReference('factory_' . $this->faker->numberBetween(1, 10)));
            $this->addReference("gift_$i", $gift);

            $manager->persist($gift);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FactoryFixtures::class,
            GiftCodeFixtures::class,
        ];
    }
}
