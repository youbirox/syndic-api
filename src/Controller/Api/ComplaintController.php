<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Residence;
use App\Entity\Building;
use App\Repository\ComplaintRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\TenantContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/complaints')]
final class ComplaintController extends AbstractController
{


    #[Route('', methods: ['GET'])]
    public function list(ComplaintRepository $repo,TenantContext $tenantContext): JsonResponse
    {
        $data = [];
        $user = $this->getUser();
        $roles = $user->getRoles();

    $residence = $tenantContext->getResidence();
    
    if (!$residence) {
        throw $this->createNotFoundException("No tenant");
     }elseif ($residence != $user->getResidence()) {
        throw $this->createNotFoundException("Acces denided");
    }

    
    
if (in_array('ROLE_SYNDIC', $roles)) {
    // syndic : seulement sa résidence
    $complaints = $repo->findBy([
        'residence' => $user->getResidence()
    ]);

} else {
    // résident : seulement ses propres cotisations

    $complaints = $repo->findBy([
        'user' => $user
    ]);
}

    $data = [];

        foreach($complaints as $c){
            $data[] = [
                'id' => $c->getId(),
                'message' => $c->getMessage(),
                'status' => $c->getStatus(),
                'createdAt' => $c->getCreatedAt(),
                'résident'  => $c->getUser()->getEmail(),
                'résidence'  => $c->getResidence()->getName(),

                
            ];

        }

        return $this->json($data);
//     return $this->json(
//     $complaints,
//     200,
//     [],
//     [
//         'groups' => ['complaint:list']
//     ]
// );
    }


}
