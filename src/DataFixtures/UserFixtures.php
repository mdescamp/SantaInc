<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('santa@santa.fr')
            ->setPassword($this->passwordHasher->hashPassword($user, 'rootroot'))
            ->setRoles(['ROLE_SANTA']);
        $this->addReference('user_santa', $user);
        $manager->persist($user);

        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail("user_$i@santa.fr")
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordHasher->hashPassword($user, 'rootroot'));
            $this->addReference("user_$i", $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
