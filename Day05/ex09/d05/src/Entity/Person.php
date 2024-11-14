<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $enable = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $bankaccountnum = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    // Relation OneToOne avec BankAccount
    #[ORM\OneToOne(mappedBy: 'person', cascade: ['persist', 'remove'])]
    private ?BankAccount $bankAccount = null;


    // Relation OneToOne avec BankAccount
    #[ORM\OneToOne(mappedBy: 'person', cascade: ['persist', 'remove'])]
    private ?Address $address_str = null;

    public function __construct()
    {
        // Initialisation des collections, ici il n'est plus nécessaire car on a un OneToOne avec BankAccount
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): static
    {
        $this->enable = $enable;
        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getBankAccountNum(): ?string
    {
        return $this->bankaccountnum;
    }

    public function setBankAccountNum(string $bankaccountnum): static
    {
        $this->bankaccountnum = $bankaccountnum;
        return $this;
    }

    // Getter et Setter pour le BankAccount associé
    public function getBankAccount(): ?BankAccount
    {
        return $this->bankAccount;
    }

    public function setBankAccount(?BankAccount $bankAccount): static
    {
        $this->bankAccount = $bankAccount;
        if ($bankAccount !== null) {
            $bankAccount->setPerson($this);  // Associer la personne au BankAccount
        }
        return $this;
    }
    
    // Getter et Setter pour le BankAccount associé
    public function getAddressStr(): ?Address
    {
        return $this->bankAccount;
    }

    public function setAddressStr(?Address $address_str): static
    {
        $this->address_str = $address_str;
        if ($address_str !== null) {
            $address_str->setPerson($this);  // Associer la personne au BankAccount
        }
        return $this;
    }
}
