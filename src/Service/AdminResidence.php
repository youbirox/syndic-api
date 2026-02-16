<?php
namespace App\Service;

use App\Entity\Residence;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminResidence
{

public function __construct(
    private EntityManagerInterface $em,
    private UserPasswordHasherInterface $hasher
){}


public function createResidenceWithSyndic(
    string $name,
    string $subdomain,
    string $syndicEmail,
    string $syndicPassword

): array
{
    // vérifier subdomain unique

           $existingResidence = $this->em->getRepository(Residence::class)
            ->findOneBy(['subdomain' => $subdomain]);

        if ($existingResidence) {
            throw new \RuntimeException('Subdomain already exists');
        }

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => $syndicEmail]);

        if ($existingUser) {
            throw new \RuntimeException('Email already exists');
        };

        //add residence

        $residence = new Residence();
        $residence->setName($name);
        $residence->setSubdomain($subdomain);
        
        //add user (Syndic)

        $syndic = new User();
        $syndic->setEmail($syndicEmail);
        $syndic->setRoles(['ROLE_SYNDIC']);
        $syndic->setPassword($this->hasher->hashPassword($syndic,$syndicPassword));
        
        $syndic->setResidence($residence);


        //add syndic in residence
        $residence->setSyndic($syndic);
        
        //persist

        $this->em->persist($residence);
        $this->em->persist($syndic);
        $this->em->flush();
        
        return [$residence, $syndic];
}
}
