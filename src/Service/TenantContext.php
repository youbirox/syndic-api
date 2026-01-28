<?php
namespace App\Service;

use App\Entity\Residence;

class TenantContext
{
    private ?Residence $residence = null;

    public function setResidence(?Residence $residence): void
    {
        $this->residence = $residence;
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
