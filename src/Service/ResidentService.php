<?php
namespace App\Service;

use App\Entity\Apartment;
use App\Entity\Building;
use App\Entity\Residence;
use App\Entity\User;
use App\Repository\ApartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResidentService
{

public function __construct(
    private EntityManagerInterface $em,
    private UserPasswordHasherInterface $hasher,
    private ApartmentRepository $repoApartment,
    private Security $security,
    private TenantContext $tenantContext
){}


public function createResidentWithSyndic(
    string $email,
    string $password,
    string $appart

):User
{
    

        $user = $this->security->getUser();
        $residence = $this->tenantContext->getResidence();

        if (!$residence) {
            throw new \DomainException('No tenant');
        }

        if ($residence->getId() !== $user->getResidence()?->getId()) {
            throw new \DomainException('User does not belong to this residence');
        }

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($existingUser) {
            throw new \RuntimeException('Email already exists');
        };


        $apartment = $this->em->getRepository(Apartment::class)
        ->find($appart);


        //add resident

        $resident = new User();
        $resident->setEmail($email);
        $resident->setPassword($this->hasher->hashPassword($resident,$password));
        $resident->setRoles(['ROLE_RESIDENT']);
        $resident->setResidence($residence);
        
        $resident->setBuilding($apartment->getBuilding());

        

        //Update Appartment
       
        $apartment->setResident($resident);
  


        //persist

        $this->em->persist($resident);
        $this->em->flush();
        
        return $resident;
}
}
