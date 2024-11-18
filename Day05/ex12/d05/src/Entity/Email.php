<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
class Email
{

    #[ORM\OneToOne(targetEntity: Informations::class, inversedBy: 'email')]
    private ?Informations $informations = null;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->informations ? $this->informations->getId() : null;

    }

    public function getInformations(): ?informations
    {
        return $this->informations;
    }

    public function setInformations(?Informations $informations): static
    {
        $this->informations = $informations;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
