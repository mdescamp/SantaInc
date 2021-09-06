<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class FactoryFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $factory = new Factory();
            $factory
                ->setName($this->faker->company)
                ->addUser($this->getReference('user_santa'))
                ->addUser($this->getReference('user_' . $this->faker->numberBetween(1, 10)));
            $this->addReference("factory_$i", $factory);
            $manager->persist($factory);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
