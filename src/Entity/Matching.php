<?php

namespace App\Entity;

use App\Repository\MatchingRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: MatchingRepository::class)]
class Matching
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name = ''; // ✅ Ensures property is initialized

    #[ORM\Column(type: 'string', length: 255)]
    private string $sujetRencontre = ''; // ✅ Ensures property is initialized

    #[ORM\Column(type: 'integer')]
    private int $numTable;

    #[ORM\Column(type: 'integer')]
    private int $nbrPersonneMatchy;

    #[ORM\Column(type: 'string', length: 255)]
    private string $image;

    #[ORM\OneToMany(mappedBy: 'matching', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\ManyToOne(inversedBy: 'matchings')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'matchings')]
    #[ORM\JoinTable(name: 'matching_user')]
    private Collection $assessors;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->assessors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSujetRencontre(): string
    {
        return $this->sujetRencontre;
    }

    public function setSujetRencontre(string $sujetRencontre): self
    {
        $this->sujetRencontre = $sujetRencontre;
        return $this;
    }

    public function getNumTable(): int
    {
        return $this->numTable;
    }

    public function setNumTable(int $numTable): self
    {
        $this->numTable = $numTable;
        return $this;
    }

    public function getNbrPersonneMatchy(): int
    {
        return $this->nbrPersonneMatchy;
    }

    public function setNbrPersonneMatchy(int $nbrPersonneMatchy): self
    {
        $this->nbrPersonneMatchy = $nbrPersonneMatchy;
        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setMatching($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMatching() === $this) {
                $message->setMatching(null);
            }
        }

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
     * @return Collection|User[]
     */
    public function getAssessors(): Collection
    {
        return $this->assessors;
    }

    public function addAssessor(User $assessor): self
    {
        if (!$this->assessors->contains($assessor)) {
            $this->assessors[] = $assessor;
        }

        return $this;
    }

    public function removeAssessor(User $assessor): self
    {
        $this->assessors->removeElement($assessor);

        return $this;
    }
}