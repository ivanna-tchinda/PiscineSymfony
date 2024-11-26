<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Employee::class, inversedBy: 'subordonnees')]
    #[ORM\JoinColumn(name: 'superieur_id', referencedColumnName: 'id')]
    private ?Employee $superieur = null;

    #[ORM\OneToMany(mappedBy: 'superieur', targetEntity: Employee::class)]
    private Collection $subordonnees;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    private string $firstname;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(message: "L'email '{{ value }}' n'est pas un email valide.")]
    private string $email;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $employed_since = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $employed_until = null;

    #[ORM\Column]
    private ?int $salary = null;

    #[Column(type: "integer", enumType: Hours::class)]
    private Hours $hours;

    #[Column(type: "string", enumType: Position::class)]
    private Position $position;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->subordonnees = new ArrayCollection();
    }

    // Getters et setters

    public function getSuperieur(): ?Employee
    {
        return $this->superieur;
    }

    public function setSuperieur(?Employee $superieur): self
    {
        $this->superieur = $superieur;
        return $this;
    }

    public function getSubordonnees(): Collection
    {
        return $this->subordonnees;
    }

    public function addSubordonne(Employee $subordonne): self
    {
        if (!$this->subordonnees->contains($subordonne)) {
            $this->subordonnees[] = $subordonne;
            $subordonne->setSuperieur($this);
        }

        return $this;
    }

    public function removeSubordonne(Employee $subordonne): self
    {
        if ($this->subordonnees->removeElement($subordonne)) {
            // Si l'élément est retiré, on dissocie le supérieur
            if ($subordonne->getSuperieur() === $this) {
                $subordonne->setSuperieur(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getHours(): ?int
    {
        return $this->hours->value;
    }

    public function setHours(int $hours): static
    {
        $this->hours = $hours;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->hours->value;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

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

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getEmployedSince(): ?\DateTimeInterface
    {
        return $this->employed_since;
    }

    public function setEmployedSince(\DateTimeInterface $employed_since): static
    {
        $this->employed_since = $employed_since;

        return $this;
    }

    public function getEmployedUntil(): ?\DateTimeInterface
    {
        return $this->employed_until;
    }

    public function setEmployedUntil(\DateTimeInterface $employed_until): static
    {
        $this->employed_until = $employed_until;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): static
    {
        $this->salary = $salary;

        return $this;
    }
}
