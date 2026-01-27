<?php

namespace App\DataFixtures;

use App\Entity\Building;
use App\Entity\Residence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BuildingFixtures extends Fixture implements DependentFixtureInterface
{
    public const BUILDING_A = 'building_a';
    public const BUILDING_B = 'building_b';

    public function load(ObjectManager $manager): void
    {
        $residence = $this->getReference(ResidenceFixtures::ORCHIDEE , Residence::class);

        $buildingA = new Building();
        $buildingA->setName('Immeuble A');
        $buildingA->setResidence($residence);

        $buildingB = new Building();
        $buildingB->setName('Immeuble B');
        $buildingB->setResidence($residence);

        $manager->persist($buildingA);
        $manager->persist($buildingB);
        $manager->flush();

        $this->addReference(self::BUILDING_A, $buildingA);
        $this->addReference(self::BUILDING_B, $buildingB);
    }

    public function getDependencies(): array
    {
        return [
            ResidenceFixtures::class,
        ];
    }
}
