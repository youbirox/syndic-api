<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN = 'admin';
    public const SYNDIC = 'syndic';

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ADMIN
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->hasher->hashPassword($admin, 'password')
        );

        $manager->persist($admin);
        $this->addReference(self::ADMIN, $admin);

        // SYNDIC
        $syndic = new User();
        $syndic->setEmail('syndic@orchidee.ma');
        $syndic->setRoles(['ROLE_SYNDIC']);
        $syndic->setPassword(
            $this->hasher->hashPassword($syndic, 'password')
        );

        $manager->persist($syndic);
        $this->addReference(self::SYNDIC, $syndic);

        $manager->flush();
    }
}
