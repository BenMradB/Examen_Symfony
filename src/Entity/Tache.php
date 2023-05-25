<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dat_limite = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $statut = [];

    #[ORM\ManyToOne(inversedBy: 'taches')]
    private ?Utulisateur $utulisateur = null;

    public function __construct()
    {
        $this->statut = ['A Faire', 'En Cours', 'Terminer'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDatLimite(): ?\DateTimeInterface
    {
        return $this->dat_limite;
    }

    public function setDatLimite(\DateTimeInterface $dat_limite): self
    {
        $this->dat_limite = $dat_limite;

        return $this;
    }

    public function getStatut(): array
    {
        return $this->statut;
    }

    public function setStatut(array $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUtulisateur(): ?Utulisateur
    {
        return $this->utulisateur;
    }

    public function setUtulisateur(?Utulisateur $utulisateur): self
    {
        $this->utulisateur = $utulisateur;

        return $this;
    }
}
