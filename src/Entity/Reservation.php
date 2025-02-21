<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Evenement $evenement = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "reservations")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_booking = null;

    #[ORM\Column]
    private ?int $nbr_places = null;

    #[ORM\Column(length: 255)]
    private ?string $statut_booking = null;

    #[ORM\Column(length: 255)]
    private ?string $moyenPayement_booking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateBooking(): ?\DateTimeInterface
    {
        return $this->date_booking;
    }

    public function setDateBooking(\DateTimeInterface $date_booking): static
    {
        $this->date_booking = $date_booking;

        return $this;
    }

    public function getNbrPlaces(): ?int
    {
        return $this->nbr_places;
    }

    public function setNbrPlaces(int $nbr_places): static
    {
        $this->nbr_places = $nbr_places;

        return $this;
    }

    public function getStatutBooking(): ?string
    {
        return $this->statut_booking;
    }

    public function setStatutBooking(string $statut_booking): static
    {
        $this->statut_booking = $statut_booking;

        return $this;
    }

    public function getMoyenPayementBooking(): ?string
    {
        return $this->moyenPayement_booking;
    }

    public function setMoyenPayementBooking(string $moyenPayement_booking): static
    {
        $this->moyenPayement_booking = $moyenPayement_booking;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }



    public function setEvenement(?Evenement $evenement): static
    {
        $this->evenement = $evenement;

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