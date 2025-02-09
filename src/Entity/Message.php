<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Matching $matching = null;
    

   
    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $user = null;

    #[ORM\Column]
    private ?bool $updated_message = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu_message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isUpdatedMessage(): ?bool
    {
        return $this->updated_message;
    }

    public function setUpdatedMessage(bool $updated_message): static
    {
        $this->updated_message = $updated_message;

        return $this;
    }

    public function getContenuMessage(): ?string
    {
        return $this->contenu_message;
    }

    public function setContenuMessage(string $contenu_message): static
    {
        $this->contenu_message = $contenu_message;

        return $this;
    }

    
    public function getMatching(): ?Matching
    {
        return $this->matching;
    }

    public function setMatching(?Matching $matching): static
    {
        $this->matching = $matching;

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
}
