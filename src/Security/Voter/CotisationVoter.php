<?php

namespace App\Security\Voter;

use App\Service\TenantContext;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class CotisationVoter extends Voter
{
    public const VIEW = 'COTISATION_VIEW';
    public const EDIT = 'COTISATION_EDIT';

    public function __construct(private TenantContext $tenantContext) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT]) && $subject instanceof Cotisation;
    }

    protected function voteOnAttribute(string $attribute, mixed $cotisation, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Admin : accès global
        if (in_array('ROLE_ADMIN', $user->getRoles())) return true;

        // Syndic : accès si même résidence
        if (in_array('ROLE_SYNDIC', $user->getRoles())) {
            return $cotisation->getResidence() === $this->tenantContext->getResidence();
        }

        // Résident : accès si c’est sa propre cotisation
        if (in_array('ROLE_RESIDENT', $user->getRoles())) {
            return $cotisation->getResident() === $user;
        }

        return false;
    }
}
