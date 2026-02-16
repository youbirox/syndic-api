<?php

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\Residence;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResidentFixtures extends Fixture implements DependentFixtureInterface
{
    public const RESIDENT_PREFIX = 'resident_';

    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $resident = new User();
            $resident->setEmail("resident{$i}@orchidee.ma");
            $resident->setRoles(['ROLE_RESIDENT']);

            $resident->setResidence(
                $this->getReference(ResidenceFixtures::ORCHIDEE, Residence::class)
            );

            $buildingReference = $i % 2 === 0
                ? BuildingFixtures::BUILDING_A
                : BuildingFixtures::BUILDING_B;

            $resident->setBuilding(
                $this->getReference($buildingReference, Building::class)
            );

            $resident->setPassword(
                $this->hasher->hashPassword($resident, 'password')
            );

            $manager->persist($resident);

            // ✅ indispensable
            $this->addReference(self::RESIDENT_PREFIX.$i, $resident);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ResidenceFixtures::class,
            BuildingFixtures::class,
        ];
    }
}
