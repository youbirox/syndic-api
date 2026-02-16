<?php

namespace App\Controller\Api;

use App\Service\AnnualCotisationGenerator;
use App\Service\CotisationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/subscription')]
final class SubscriptionController extends AbstractController
{
    #[Route('/generate', methods: ['POST'])]
    public function generate(Request $request,
        AnnualCotisationGenerator $generator): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_SYNDIC');
        $data = json_decode($request->getContent(), true);

        $year = $data['year'] ?? null;
        $amount = $data['amount'] ?? null;

        if (!$year || !$amount) {
            return $this->json(
                ['error' => 'Year and amount are required'],
                400
            );
        }

        
        $generator->generateForYear($year,$amount);

        return $this->json([
            'message' => "Cotisations $year générées avec succès"
        ]);
    }

    #[Route('', methods: ['GET'])]
    public function list(CotisationService $service): JsonResponse
    {
        
        $cotisations = $service->getList();

        $data = array_map(fn($c) => [
            'id' => $c->getId(),
            'year' => $c->getYear(),
            'amount' => $c->getAmount(),
            'status' => $c->getStatus(),
            'createdAt' => $c->getCreatedAt(),
            'resident' => $c->getResident()?->getEmail(),
            'apartment' => $c->getApartment()?->getNumber(),
        ], $cotisations);

        return $this->json($data);
    }
}
