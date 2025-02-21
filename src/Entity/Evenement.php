<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_evenement = null;

    #[ORM\Column(length: 255)]
    private ?string $description_event = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_event = null;

    #[ORM\Column]
    private ?float $prix_event = null;

    #[ORM\Column(length: 255)]
    private ?string $image_event = null;

    #[ORM\Column(length: 255)]
    private ?string $type_event = null;

    #[ORM\Column]
    private ?int $capaciteMax = null;

         /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'evenement')]
    private Collection $reservations;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitreEvenement(): ?string
    {
        return $this->titre_evenement;
    }

    public function setTitreEvenement(string $titre_evenement): static
    {
        $this->titre_evenement = $titre_evenement;

        return $this;
    }

    public function getDescriptionEvent(): ?string
    {
        return $this->description_event;
    }

    public function setDescriptionEvent(string $description_event): static
    {
        $this->description_event = $description_event;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->date_event;
    }

    public function setDateEvent(\DateTimeInterface $date_event): static
    {
        $this->date_event = $date_event;

        return $this;
    }

    public function getPrixEvent(): ?float
    {
        return $this->prix_event;
    }

    public function setPrixEvent(float $prix_event): static
    {
        $this->prix_event = $prix_event;

        return $this;
    }

    public function getImageEvent(): ?string
    {
        return $this->image_event;
    }

    public function setImageEvent(string $image_event): static
    {
        $this->image_event = $image_event;

        return $this;
    }

    public function getTypeEvent(): ?string
    {
        return $this->type_event;
    }

    public function setTypeEvent(string $type_event): static
    {
        $this->type_event = $type_event;

        return $this;
    }

    public function getCapaciteMax(): ?int
    {
        return $this->capaciteMax;
    }

    public function setCapaciteMax(int $capaciteMax): static
    {
        $this->capaciteMax = $capaciteMax;

        return $this;
    }
}
