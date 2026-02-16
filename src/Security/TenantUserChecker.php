<?php
// src/Security/TenantUserChecker.php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class TenantUserChecker implements UserCheckerInterface
{
    public function __construct(private RequestStack $requestStack) {}

    // Vérification avant authentification
    public function checkPreAuth(UserInterface $user)
    {
        $request = $this->requestStack->getCurrentRequest();
        $host = $request->getHost(); // ex: orchidee.localhost
        $subdomain = explode('.', $host)[0];

        
        // Admin global ? On autorise
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return;
        }

        // Vérifier si le user appartient au subdomain
        if (!$user->getResidence() || $user->getResidence()->getSubdomain() !== $subdomain) {
            throw new CustomUserMessageAuthenticationException(
                'Sous-domaine invalide pour cet utilisateur'
            );
        }

        if (!$user->isActif()) {
            throw new CustomUserMessageAccountStatusException('Compte désactivé.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        // Pas nécessaire pour l’instant
    }
}
