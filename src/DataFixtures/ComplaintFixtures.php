<?php

namespace App\DataFixtures;

use App\Entity\Complaint;
use App\Entity\Residence;
use App\Entity\User;
use App\Entity\Apartment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ComplaintFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var Residence $residence */
        $residence = $this->getReference(ResidenceFixtures::ORCHIDEE, Residence::class);

        // Complaint 1 (resident1 -> apartment A1)
        /** @var User $resident1 */
        $resident1 = $this->getReference(ResidentFixtures::RESIDENT_PREFIX.'1', User::class);

        /** @var Apartment $aptA1 */
        $aptA1 = $this->getReference(ApartmentFixtures::APARTMENT_PREFIX.'A1', Apartment::class);

        $c1 = new Complaint();
        $c1->setResidence($residence);
        $c1->setUser($resident1);
        $c1->setMessage("L’ascenseur ne fonctionne pas depuis 2 jours.");
        $c1->setStatus("OPEN");
        $c1->setCreatedAt(new \DateTime());
        $manager->persist($c1);

        // Complaint 2 (resident2 -> apartment B2)
        /** @var User $resident2 */
        $resident2 = $this->getReference(ResidentFixtures::RESIDENT_PREFIX.'2', User::class);

        /** @var Apartment $aptB2 */
        $aptB2 = $this->getReference(ApartmentFixtures::APARTMENT_PREFIX.'B2', Apartment::class);

        $c2 = new Complaint();
        $c2->setResidence($residence);
        $c2->setUser($resident2);
        $c2->setMessage("La lumière du couloir est HS au 2ème étage.");
        $c2->setStatus("IN_PROGRESS");
        $c2->setCreatedAt(new \DateTime());
        $manager->persist($c2);

        // Complaint 3 (resident3 -> apartment A3)
        /** @var User $resident3 */
        $resident3 = $this->getReference(ResidentFixtures::RESIDENT_PREFIX.'3', User::class);

        /** @var Apartment $aptA3 */
        $aptA3 = $this->getReference(ApartmentFixtures::APARTMENT_PREFIX.'A3', Apartment::class);

        $c3 = new Complaint();
        $c3->setResidence($residence);
        $c3->setUser($resident3);
        $c3->setMessage("Les escaliers sont sales, merci de programmer un nettoyage.");
        $c3->setStatus("OPEN");
        $c3->setCreatedAt(new \DateTime());
        $manager->persist($c3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ResidenceFixtures::class,
            ResidentFixtures::class,
            ApartmentFixtures::class,
        ];
    }
}
