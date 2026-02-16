<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ResidentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/resident')]
final class ResidentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(UserRepository $repo,TenantContext $tenantContext): JsonResponse
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

  
        $residents = $repo->findResidentsByResidence($user->getResidence());
        $count = $repo->countResidentsByResidence($user->getResidence());
        $countInactif = $repo->countResidentsInactif($user->getResidence());
        $countActif = $repo->countResidentsActif($user->getResidence());

        $data = [];

        foreach($residents as $r){
           $data[] = [
        'id' => $r->getId(),
        'email' => $r->getEmail(),
        'Immeuble' => $r->getBuilding() ? $r->getBuilding()->getName() : null,
        'Appartement' => $r->getApartment() ? $r->getApartment()->getNumber() : null,
        'etat' => $r->isActif(),
    ];

        }

        return $this->json([
            'count' => $count,
            'countInactif' => $countInactif,
            'countActif' => $countActif,
            'data' => $data
            ]);
        
    }

    #[Route('/{id}', methods: ['PATCH'])]
    public function edit(EntityManagerInterface $em,Request $request,int $id,TenantContext $tenantContext): JsonResponse
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

        $data = json_decode($request->getContent(), true);
        $userEdit = $em->getRepository(User::class)->find($id);
        if (!$userEdit) {
        return $this->json(
            ['error' => 'User not found'],
            Response::HTTP_NOT_FOUND
        );
    }

        if (isset($data['email'])) {
        $userEdit->setEmail($data['email']);
        }

        //Logic if a resident sells his apartment to another resident
    
        // 1) Must initiaize all relations with this resident 
        // If case coched / Table user retire building_ID and residence_ID

        // Retire resident_ID in table Apartment

        


        // if (isset($data['apartmentId'])) {
        // $userEdit->setEmail($data['email']);
        // }
    $em->flush();


        return $this->json(['Update successful'],Response::HTTP_OK);
        
    }

    #[Route('', methods: ['POST'])]
    public function createResidentBySyndic(ResidentService $residentService,Request $request ): JsonResponse
    {


        $data = json_decode($request->getContent(),true);    

     if (
           
            empty($data['email']) ||
            empty($data['apartmentId']) ||
            empty($data['password'])

            ) {
            
            return $this->json(['error' => 'Name, , Email, Password are required'],400);
        }

                
        try {
            
              
                $residentService->createResidentWithSyndic(
                $data['email'],
                $data['password'],
                $data['apartmentId']
            );
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        }
           
    return $this->json([
            'message' => 'Created successfully',
        ],201);
}

}