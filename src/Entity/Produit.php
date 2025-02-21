<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

 

    #[ORM\Column(length: 255)]
    private ?string $nom_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $description_produit = null;

    #[ORM\Column]
    private ?float $prix_produit = null;

    #[ORM\Column]
    private ?int $stock_produit = null;

    #[ORM\Column(length: 255)]
    private ?string $type_produit = null;

    #[ORM\Column]
    private ?bool $etat_produit = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'produit')]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

   

    

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): static
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->description_produit;
    }

    public function setDescriptionProduit(string $description_produit): static
    {
        $this->description_produit = $description_produit;

        return $this;
    }

    public function getPrixProduit(): ?float
    {
        return $this->prix_produit;
    }

    public function setPrixProduit(float $prix_produit): static
    {
        $this->prix_produit = $prix_produit;

        return $this;
    }

    public function getStockProduit(): ?int
    {
        return $this->stock_produit;
    }

    public function setStockProduit(int $stock_produit): static
    {
        $this->stock_produit = $stock_produit;

        return $this;
    }

    public function getTypeProduit(): ?string
    {
        return $this->type_produit;
    }

    public function setTypeProduit(string $type_produit): static
    {
        $this->type_produit = $type_produit;

        return $this;
    }

    public function isEtatProduit(): ?bool
    {
        return $this->etat_produit;
    }

    public function setEtatProduit(bool $etat_produit): static
    {
        $this->etat_produit = $etat_produit;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setIdProduit($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getIdProduit() === $this) {
                $commande->setIdProduit(null);
            }
        }

        return $this;
    }
}
