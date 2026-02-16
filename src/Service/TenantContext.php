<?php
namespace App\Service;

use App\Entity\Residence;

class TenantContext
{
    private ?Residence $residence = null;
    private bool $isAdminContext = false;

    public function setResidence(?Residence $residence): void
    {
        $this->residence = $residence;
    }
    
    public function setAdminContext(): void
    {
        $this->isAdminContext = true;
    }

    public function isAdminContext(): bool
    {
        return $this->isAdminContext;
    }

    public function getResidence(): ?Residence
    {
        return $this->residence;
    }

    public function hasResidence(): bool
    {
        return $this->residence !== null;
    }
}
