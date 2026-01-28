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

    public function onKernelRequest(RequestEvent $event): void
    {
        // uniquement la requête principale
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $host = $request->getHost(); // ex: orchidee.syndic.ma

        $subdomain = explode('.', $host)[0];

        // sous-domaines à ignorer
        $skip = ['www', 'api', 'admin', 'localhost', '127'];

        if (in_array($subdomain, $skip, true)) {
            return;
        }

        $residence = $this->em->getRepository(Residence::class)
            ->findOneBy(['subdomain' => $subdomain]);

        if (!$residence) {
            throw new NotFoundHttpException("Résidence introuvable pour: $subdomain");
        }

        $this->tenantContext->setResidence($residence);
    }
}
