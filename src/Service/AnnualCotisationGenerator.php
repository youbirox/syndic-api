<?php
namespace App\Service;

use App\Entity\Subscription;
use App\Repository\ApartmentRepository;
use App\Repository\SubscriptionRepository;
use App\Service\TenantContext;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AnnualCotisationGenerator
{
    public function __construct(
        private ApartmentRepository $apartmentRepo,
        private SubscriptionRepository $subscriptionRepo,
        private TenantContext $tenantContext,
        private EntityManagerInterface $em,
        private Security $security
    ) {}

    public function generateForYear(int $year,int $amount): void
    {
        
        $residence = $this->tenantContext->getResidence();
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedHttpException('Not authenticated');
        }

        if ($residence !== $user->getResidence()) {
            throw new AccessDeniedHttpException('Access denied');
        }

      

        if (!$residence) {
            throw new \Exception("No tenant");
        }

        
        $apartments = $this->apartmentRepo->findByResidence($residence);

        foreach ($apartments as $apartment) {

            // éviter doublons
            if ($this->subscriptionRepo->existsForApartmentAndYear($apartment, $year)) {
                throw new \RuntimeException("Cotisations déjà générées pour $year");
               // continue;
            }

            $subscription = new Subscription();
            $subscription->setResidence($residence);
            $subscription->setApartment($apartment);
            $subscription->setResident($apartment->getResident());
            $subscription->setYear($year);
            $subscription->setAmount($amount); 
            $subscription->setCreatedAt(new \DateTime()); 
            
            $subscription->setStatus('UNPAID');

            $this->em->persist($subscription);
        }

        $this->em->flush();
        
    }
}
