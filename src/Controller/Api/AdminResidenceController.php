<?php

namespace App\Controller\Api;


use App\Entity\Residence;
use App\Entity\User;
use App\Service\AdminResidence;
use App\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/admin/residences')]
final class AdminResidenceController extends AbstractController
{

    #[Route('',methods:['POST'])]
    public function createResidenceWithSyndic(Request $request,AdminResidence $adminResidence):JsonResponse
    {

       // $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $data = json_decode($request->getContent(),true);

        if (
            empty($data['name']) ||
            empty($data['subdomain']) ||
            empty($data['syndicEmail']) ||
            empty([$data['syndicPassword']])
            ) {
            
            return $this->json(['error' => 'name, subdomain, syndicEmail, syndicPassword are required'],400);
        }
        
        try {
           [$residence, $syndic] = $adminResidence->createResidenceWithSyndic(
                $data['name'],
                $data['subdomain'],
                $data['syndicEmail'],
                $data['syndicPassword']
            );
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], 409);
        }
          

        return $this->json([
            'message' => 'Created successfully',
            'residence' => [
                'id' => $residence->getId(),
                'name' => $residence->getName(),
                'subdomain' => $residence->getSubdomain(),
            ],
            'syndic' => [
                'id' => $syndic->getId(),
                'email' => $syndic->getEmail(),
                'roles' => $syndic->getRoles(),
            ],
            'tenantUrl' => sprintf('http://%s.localhost:8000', $residence->getSubdomain())
        ],201);


    }

}
