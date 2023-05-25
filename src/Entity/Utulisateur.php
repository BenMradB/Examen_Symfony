<?php

namespace App\Entity;

use App\Repository\UtulisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtulisateurRepository::class)]
class Utulisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_email = null;

    #[ORM\Column(length: 255)]
    private ?string $met_passe = null;

    #[ORM\OneToMany(mappedBy: 'utulisateur', targetEntity: Tache::class)]
    private Collection $taches;

    public function __construct()
    {
        $this->taches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresseEmail(): ?string
    {
        return $this->adresse_email;
    }

    public function setAdresseEmail(string $adresse_email): self
    {
        $this->adresse_email = $adresse_email;

        return $this;
    }

    public function getMetPasse(): ?string
    {
        return $this->met_passe;
    }

    public function setMetPasse(string $met_passe): self
    {
        $this->met_passe = $met_passe;

        return $this;
    }

    /**
     * @return Collection<int, Tache>
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Tache $tach): self
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
            $tach->setUtulisateur($this);
        }

        return $this;
    }

    public function removeTach(Tache $tach): self
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getUtulisateur() === $this) {
                $tach->setUtulisateur(null);
            }
        }

        return $this;
    }
}
