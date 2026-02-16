<?php

namespace App\Entity;

use App\Repository\ComplaintRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComplaintRepository::class)]
class Complaint
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['complaint:list'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['complaint:list'])]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    #[Groups(['complaint:list'])]
    private ?string $status = null;

    #[ORM\Column]
    #[Groups(['complaint:list'])]
    private ?\DateTime $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'complaints')]
    #[Groups(['complaint:list'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'complaints')]
    #[Groups(['complaint:list'])]
    private ?Residence $residence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getResidence(): ?Residence
    {
        return $this->residence;
    }

    public function setResidence(?Residence $residence): static
    {
        $this->residence = $residence;

        return $this;
    }
}
