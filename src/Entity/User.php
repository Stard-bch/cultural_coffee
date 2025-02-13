<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_user = null;

    #[ORM\Column(length: 255)]
    private ?string $role_user = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_user = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_user = null;

    #[ORM\Column(length: 255)]
    private ?string $email_user = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $telephone_user = null;

    #[ORM\Column(length: 255)]
    private ?string $photo_user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance_user = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commentaire::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Liked::class, cascade: ['persist', 'remove'])]
    private Collection $likes;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeUser(): ?string
    {
        return $this->type_user;
    }

    public function setTypeUser(string $type_user): static
    {
        $this->type_user = $type_user;

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

    public function setPhotoUser(string $photo_user): static
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

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Liked>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Liked $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Liked $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        // Assuming role_user contains a single role
        return [$this->role_user];
    }

    public function getPassword(): ?string
    {
        
        return null;
    }

    public function getSalt(): ?string
    {
        // Not needed when using modern algorithms like bcrypt or sodium
        return null;
    }

    public function getUsername(): string
    {
        // Assuming email_user is used as the username
        return $this->email_user;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    public function getUserIdentifier(): string
    {
        return $this->email_user;
    }
}
