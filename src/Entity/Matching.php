<?php

namespace App\Entity;

use App\Repository\MatchingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchingRepository::class)]
class Matching
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_table = null;

    #[ORM\Column(length: 255)]
    private ?string $sujet_rencontre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $feedback = null;

    #[ORM\Column(nullable: true)]
    private ?int $rating = null;

    #[ORM\Column]
    private ?int $nbr_personneMatchy = null;

         /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'matching')]
    private Collection $messages;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumTable(): ?int
    {
        return $this->num_table;
    }

    public function setNumTable(int $num_table): static
    {
        $this->num_table = $num_table;

        return $this;
    }

    public function getSujetRencontre(): ?string
    {
        return $this->sujet_rencontre;
    }

    public function setSujetRencontre(string $sujet_rencontre): static
    {
        $this->sujet_rencontre = $sujet_rencontre;

        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(?string $feedback): static
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getNbrPersonneMatchy(): ?int
    {
        return $this->nbr_personneMatchy;
    }

    public function setNbrPersonneMatchy(int $nbr_personneMatchy): static
    {
        $this->nbr_personneMatchy = $nbr_personneMatchy;

        return $this;
    }
}
