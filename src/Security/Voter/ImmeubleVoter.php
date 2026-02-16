<?php

namespace App\Security\Voter;

use App\Service\TenantContext;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ImmeubleVoter extends Voter
{
     public const VIEW = 'IMMEUBLE_VIEW';

    public function __construct(private TenantContext $tenantContext) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::VIEW && $subject instanceof Immeuble;
    }

    protected function voteOnAttribute(string $attribute, mixed $immeuble, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) return true;
        if (in_array('ROLE_SYNDIC', $user->getRoles())) {
            return $immeuble->getResidence() === $this->tenantContext->getResidence();
        }
        if (in_array('ROLE_RESIDENT', $user->getRoles())) {
            return $user->getAppartement()?->getImmeuble() === $immeuble;
        }

        return false;
    }
}
