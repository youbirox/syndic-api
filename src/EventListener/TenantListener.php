<?php
namespace App\EventListener;

use App\Entity\Residence;
use App\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TenantListener
{
    public function __construct(
        private EntityManagerInterface $em,
        private TenantContext $tenantContext
    ) {}

    public function onKernelRequest(RequestEvent $event)
{

    // uniquement la requête principale
        if (!$event->isMainRequest()) {
            return;
        }
    $request = $event->getRequest();
    $host = $request->getHost(); // ex: orchidee.localhost

    $parts = explode('.', $host);

    // Pas de sous-domaine → interdit
    if (count($parts) < 2) {
        throw new NotFoundHttpException('Sous-domaine requis');
    }

    $subdomain = $parts[0];

    // Autoriser admin si tu veux
    if ($subdomain === 'admin') {
        return;
    }



    $residence = $this->em->getRepository(Residence::class)
        ->findOneBy(['subdomain' => $subdomain]);

    if (!$residence) {
        throw new NotFoundHttpException('Résidence introuvable');
    }

    $this->tenantContext->setResidence($residence);
}
}
