<?php

namespace App\DataFixtures;

use App\Entity\Residence;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ResidenceFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORCHIDEE = 'residence_orchidee';

    public function load(ObjectManager $manager): void
    {
        $residence = new Residence();
        $residence->setName('Résidence Orchidée');
        $residence->setSubdomain('orchidee');
        $residence->setSyndic($this->getReference(UserFixtures::SYNDIC, User::class));

        $manager->persist($residence);
        $manager->flush();

        $this->addReference(self::ORCHIDEE, $residence);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
