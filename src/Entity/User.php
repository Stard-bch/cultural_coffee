<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;
    
    #[ORM\Column(length: 255)]
    private ?string $role_user = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_user = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_user = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email_user = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $telephone_user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo_user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance_user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoleUser(): ?string
    {
        return $this->role_user;
    }

    public function setRoleUser(string $role_user): static
    {
        $this->role_user = $role_user;
        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nom_user;
    }

    public function setNomUser(string $nom_user): static
    {
        $this->nom_user = $nom_user;
        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenom_user;
    }

    public function setPrenomUser(string $prenom_user): static
    {
        $this->prenom_user = $prenom_user;
        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->email_user;
    }

    public function setEmailUser(string $email_user): static
    {
        $this->email_user = $email_user;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getTelephoneUser(): ?int
    {
        return $this->telephone_user;
    }

    public function setTelephoneUser(int $telephone_user): static
    {
        $this->telephone_user = $telephone_user;
        return $this;
    }

    public function getPhotoUser(): ?string
    {
        return $this->photo_user;
    }

    public function setPhotoUser(?string $photo_user): static
    {
        $this->photo_user = $photo_user;
        return $this;
    }

    public function getDateNaissanceUser(): ?\DateTimeInterface
    {
        return $this->dateNaissance_user;
    }

    public function setDateNaissanceUser(\DateTimeInterface $dateNaissance_user): static
    {
        $this->dateNaissance_user = $dateNaissance_user;
        return $this;
    }

    // Implement required methods for UserInterface and PasswordAuthenticatedUserInterface
    public function getRoles(): array
    {
        return [$this->role_user ?? 'ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // No sensitive temporary data stored
    }

    public function getUserIdentifier(): string
    {
        return $this->email_user;
    }
}
