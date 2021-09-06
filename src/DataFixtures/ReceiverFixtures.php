<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Receiver;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ReceiverFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $receiver = new Receiver();
            $receiver
                ->setUuid($this->faker->uuid)
                ->setCountry($this->faker->countryCode)
                ->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->addGift($this->getReference('gift_' . $this->faker->numberBetween(1, 10)));
            $manager->persist($receiver);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            GiftCodeFixtures::class,
        ];
    }
}
