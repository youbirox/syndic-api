<?php
namespace App\Service;

use App\Repository\SubscriptionRepository;
use App\Service\TenantContext;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CotisationService
{
    public function __construct(
        private SubscriptionRepository $repo,
        private Security $security,
        private TenantContext $tenantContext
    ) {}

    public function getList(): array
    {
        $user = $this->security->getUser();
        $residence = $this->tenantContext->getResidence();

        if (!$user) {
            throw new AccessDeniedHttpException('Unauthorized');
        }

        // ADMIN
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->repo->findAll();
        }

        // SYNDIC
        if (in_array('ROLE_SYNDIC', $user->getRoles())) {
            if (!$residence || $residence !== $user->getResidence()) {
                throw new AccessDeniedHttpException('Access denied');
            }
            return $this->repo->findByResidence($residence);
        }

        // RESIDENT
        return $this->repo->findByResident($user);
    }
}
