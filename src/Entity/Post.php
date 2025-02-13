<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description_post = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbr_likes = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $date_post = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'post', cascade: ['persist', 'remove'])]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Liked::class, cascade: ['persist', 'remove'])]
    private Collection $likes;

    public function __construct()
    {
        $this->date_post = new \DateTime();
        $this->nbr_likes = 0;
        $this->commentaires = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescriptionPost(): ?string
    {
        return $this->description_post;
    }

    public function setDescriptionPost(string $description_post): static
    {
        $this->description_post = $description_post;

        return $this;
    }

    public function getNbrLikes(): ?int
    {
        return $this->nbr_likes;
    }

    public function setNbrLikes(?int $nbr_likes): static
    {
        $this->nbr_likes = $nbr_likes;

        return $this;
    }

    public function getDatePost(): ?\DateTimeInterface
    {
        return $this->date_post;
    }

    public function setDatePost(\DateTimeInterface $date_post): static
    {
        $this->date_post = $date_post;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPost($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPost() === $this) {
                $commentaire->setPost(null);
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
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Liked $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }
}
