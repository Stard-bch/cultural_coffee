<?php

namespace App\Entity;

use App\Repository\PostRepository;
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

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_post = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

        /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'Post')]
    private Collection $commentaires;


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
}
