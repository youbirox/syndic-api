<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Entity\Residence;
use App\Entity\Building;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/users')]
final class UserController extends AbstractController
{
    #[Route('',methods: ['GET'])]
    public function list(EntityManagerInterface $em):JsonResponse
    {
        $users = $em->getRepository(User::class)->findAll();

        $data = [];

        foreach($users as $u){
            $data[] = [
                'id' => $u->getId(),
                'email' => $u->getEmail(),
                'roles' => $u->getRoles(),
                'residence' => $u->getResidence() ? [
                    'id' => $u->getResidence()->getId(),
                    'name' => $u->getResidence()->getName()
                ] : null,
                'building' => $u->getBuilding() ? [
                    'id' => $u->getBuilding()->getId(),
                    'name' => $u->getBuilding()->getName()
                ] : null
            ];

        }

        return $this->json($data);
    }

    #[Route('/{id}',methods: ['GET'])]
    public function show(EntityManagerInterface $em, int $id):JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'residence' => $user->getResidence() ? [
                'id' => $user->getResidence()->getId(),
                'name' => $user->getResidence()->getName()
            ] : null,
            'building' => $user->getBuilding() ? [
                'id' => $user->getBuilding()->getId(),
                'name' => $user->getBuilding()->getName()
            ] : null
        ]);

    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em,UserPasswordHasherInterface $hasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['roles'])) {
            return $this->json(['error' => 'Email and roles required'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setPassword($hasher->hashPassword($user,$data['password'] ?? 'password'));

        
        if (!empty($data['residence_id'])) {
            $residence = $em->getRepository(Residence::class)->find($data['residence_id']);
            if ($residence) $user->setResidence($residence);
        }

        
        if (!empty($data['building_id'])) {
            $building = $em->getRepository(Building::class)->find($data['building_id']);
            if ($building) $user->setBuilding($building);
        }

        $em->persist($user);
        $em->flush();

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles()
        ], 201);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, int $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) return $this->json(['error' => 'User not found'], 404);

        $data = json_decode($request->getContent(), true);

        if (!empty($data['email'])) $user->setEmail($data['email']);
        if (!empty($data['roles'])) $user->setRoles($data['roles']);
        if (!empty($data['password'])) $user->setPassword($data['password']); // hash à ajouter plus tard

        // Update residence/building
        if (isset($data['residence_id'])) {
            $residence = $em->getRepository(Residence::class)->find($data['residence_id']);
            $user->setResidence($residence ?: null);
        }
        if (isset($data['building_id'])) {
            $building = $em->getRepository(Building::class)->find($data['building_id']);
            $user->setBuilding($building ?: null);
        }

        $em->flush();

        return $this->json(['success' => true]);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $em, int $id): JsonResponse
    {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) return $this->json(['error' => 'User not found'], 404);

        $em->remove($user);
        $em->flush();

        return $this->json(['success' => true]);
    }


}
