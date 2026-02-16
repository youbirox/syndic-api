<?php

namespace App\Security\Voter;

use App\Service\TenantContext;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class AppartementVoter extends Voter
{
    public const VIEW = 'APPARTEMENT_VIEW';

    public function __construct(private TenantContext $tenantContext) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::VIEW && $subject instanceof Appartement;
    }

    protected function voteOnAttribute(string $attribute, mixed $appartement, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Admin : accès global
        if (in_array('ROLE_ADMIN', $user->getRoles())) return true;

        // Syndic : accès si même résidence
        if (in_array('ROLE_SYNDIC', $user->getRoles())) {
            return $appartement->getImmeuble()?->getResidence() === $this->tenantContext->getResidence();
        }

        // Résident : accès si il habite dans cet appartement
        if (in_array('ROLE_RESIDENT', $user->getRoles())) {
            return $user->getAppartement() === $appartement;
        }

        return false;
    }
}
