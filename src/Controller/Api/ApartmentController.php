<?php

namespace App\Controller\Api;

use App\Entity\Apartment;
use App\Repository\ApartmentRepository;
use App\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/apartment')]
final class ApartmentController extends AbstractController
{
    #[Route('', methods: ['GET'],host: '{subdomain}.localhost')]
    public function list(ApartmentRepository $repo,TenantContext $tenantContext): JsonResponse
    {
        $user = $this->getUser();
        $residence = $tenantContext->getResidence();
    
        $this->denyAccessUnlessGranted('ROLE_SYNDIC'); 
        
    if (!$residence) {
        throw $this->createNotFoundException('No tenant');
    }

    if ($residence !== $user->getResidence()) {
        return $this->json(
            ['error' => 'Access denied'],
            Response::HTTP_FORBIDDEN
        );
    }
  
        $apartment = $repo->findBy(['createdBy' => $user->getId()]);

        $data = [];

        foreach($apartment as $a){
           $data[] = [
                'id' => $a->getId(),
                'name' => $a->getNumber(),
                'building' => [
                    'id' => $a->getBuilding()->getId(),
                    'name' => $a->getBuilding()->getName(),
            ],
];


        }

        return $this->json($data);
        
    }


    #[Route('', methods: ['POST'])]
    public function createBuilding(
        Request $request,
        EntityManagerInterface $em,
        TenantContext $tenantContext
    ): JsonResponse {

    $this->denyAccessUnlessGranted('ROLE_SYNDIC'); 

    $user = $this->getUser();
    $residence = $tenantContext->getResidence();

    if (!$residence) {
        throw $this->createNotFoundException('No tenant');
    }

    if ($residence !== $user->getResidence()) {
        return $this->json(
            ['error' => 'Access denied'],
            Response::HTTP_FORBIDDEN
        );
    }

    $data = json_decode($request->getContent(), true);

    $apartment = new Apartment();
    $apartment->setName($data['name']);
    $apartment->setResidence($residence);

    $em->persist($apartment);
    $em->flush();

    return $this->json(
        [
            'id' => $apartment->getId(),
            'name' => $apartment->getNumber(),
            'residence' => $apartment->getResidence()->getName(),
        ],
        Response::HTTP_CREATED
    );
}

    #[Route('/available', methods: ['GET'])]
    public function listAvailable(
    ApartmentRepository $repo,
    TenantContext $tenantContext
    ): JsonResponse {
        
        $user = $this->getUser();
        $residence = $tenantContext->getResidence();
    
        $this->denyAccessUnlessGranted('ROLE_SYNDIC'); 
        
        
        if ($residence !== $user->getResidence()) {
        return $this->json(
            ['error' => 'Access denied'],
            Response::HTTP_FORBIDDEN
            );
        }

        if (!$residence) {
            return $this->json(['error' => 'Résidence non trouvée'], 400);
        }

        $apartments = $repo->findAvailableByResidence($residence);

        // if ($apartments->getResident() !== null) {
        //     return $this->json(['error' => 'Appartement déjà occupé'], 400);
        // }

    return $this->json(
        array_map(fn($a) => [
            'id' => $a->getId(),
            'number' => $a->getNumber()
        ], $apartments)
    );
}
}
