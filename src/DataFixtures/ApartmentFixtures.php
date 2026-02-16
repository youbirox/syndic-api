<?php

namespace App\DataFixtures;

use App\Entity\Apartment;
use App\Entity\Building;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ApartmentFixtures extends Fixture implements DependentFixtureInterface
{
    public const APARTMENT_PREFIX = 'apartment_';

    public function load(ObjectManager $manager): void
    {
        /** @var Building $buildingA */
        $buildingA = $this->getReference(BuildingFixtures::BUILDING_A, Building::class);

        /** @var Building $buildingB */
        $buildingB = $this->getReference(BuildingFixtures::BUILDING_B, Building::class);

        for ($i = 1; $i <= 5; $i++) {
            $apt = new Apartment();
            $apt->setNumber("A{$i}");
            $apt->setBuilding($buildingA);

            if (in_array($i, [1,3,5])) {
                /** @var User $resident */
                $resident = $this->getReference(ResidentFixtures::RESIDENT_PREFIX.$i, User::class);
                $apt->setResident($resident);
            }

            $manager->persist($apt);
            $this->addReference(self::APARTMENT_PREFIX.'A'.$i, $apt);
        }

        for ($i = 1; $i <= 5; $i++) {
            $apt = new Apartment();
            $apt->setNumber("B{$i}");
            $apt->setBuilding($buildingB);

            if (in_array($i, [2,4])) {
                /** @var User $resident */
                $resident = $this->getReference(ResidentFixtures::RESIDENT_PREFIX.$i, User::class);
                $apt->setResident($resident);
            }

            $manager->persist($apt);
            $this->addReference(self::APARTMENT_PREFIX.'B'.$i, $apt);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            BuildingFixtures::class,
            ResidentFixtures::class,
        ];
    }
}
