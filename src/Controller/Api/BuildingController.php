<?php

namespace App\Controller\Api;

use App\Entity\Building;
use App\Repository\BuildingRepository;
use App\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/building')]
final class BuildingController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(BuildingRepository $repo,TenantContext $tenantContext): JsonResponse
    {
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

     
        $building = $repo->findBy(['residence' => $user->getResidence()]);
    

        $data = [];

        foreach($building as $b){
            $data[] = [
                'id' => $b->getId(),
                'name' => $b->getName(),
                'résidence'  => $b->getResidence()->getName(),

                
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

    $building = new Building();
    $building->setName($data['name']);
    $building->setResidence($residence);

    $em->persist($building);
    $em->flush();

    return $this->json(
        [
            'id' => $building->getId(),
            'name' => $building->getName(),
            'residence' => $building->getResidence()->getName(),
        ],
        Response::HTTP_CREATED
    );
}
}
