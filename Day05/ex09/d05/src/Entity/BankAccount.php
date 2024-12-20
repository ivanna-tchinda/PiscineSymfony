<?php

namespace App\Entity;

use App\Repository\BankAccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BankAccountRepository::class)]
class BankAccount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'bankAccounts')]
    #[ORM\JoinColumn(nullable: false)]  // La personne doit être définie pour chaque compte bancaire
    private ?Person $person = null;


    #[ORM\Column(length: 255)]
    private ?string $bankAccountNum = null;

    public function getId(): ?int
    {
        // Retourne l'ID de la personne associée
        return $this->person ? $this->person->getId() : null;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;
        return $this;
    }

    public function getBankAccountNum(): ?string
    {
        return $this->bankAccountNum;
    }

    public function setBankAccountNum(string $bankAccountNum): static
    {
        $this->bankAccountNum = $bankAccountNum;
        return $this;
    }
}
