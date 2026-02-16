<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $user = $event->getUser();
    $payload = $event->getData();

    if (in_array('ROLE_ADMIN', $user->getRoles())) {
        $payload['subdomain'] = 'admin';
    } elseif ($user->getResidence()) {
        $payload['subdomain'] = $user->getResidence()->getSubdomain();
        $payload['residence_id'] = $user->getResidence()->getId();
    } else {
        $payload['subdomain'] = null;
    }

    $event->setData($payload);
    }
}
