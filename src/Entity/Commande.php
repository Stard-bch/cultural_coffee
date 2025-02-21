<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 

    #[ORM\Column]
    private ?int $quantite_produit = null;

    #[ORM\Column]
    private ?int $prixTotal_commande = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   

   

    public function getQuantiteProduit(): ?int
    {
        return $this->quantite_produit;
    }

    public function setQuantiteProduit(int $quantite_produit): static
    {
        $this->quantite_produit = $quantite_produit;

        return $this;
    }

    public function getPrixTotalCommande(): ?int
    {
        return $this->prixTotal_commande;
    }

    public function setPrixTotalCommande(int $prixTotal_commande): static
    {
        $this->prixTotal_commande = $prixTotal_commande;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

   
    }


