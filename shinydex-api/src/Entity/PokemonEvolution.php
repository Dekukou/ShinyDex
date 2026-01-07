<?php

namespace App\Entity;

use App\Repository\PokemonEvolutionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonEvolutionRepository::class)]
class PokemonEvolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $fromSpecies = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $toSpecies = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EvolutionTrigger $evolutionTrigger = null;

    #[ORM\ManyToOne]
    private ?Item $item = null;

    #[ORM\Column(nullable: true)]
    private ?int $minLevel = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?RegionForm $requiredRegionForm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromSpecies(): ?Pokemon
    {
        return $this->fromSpecies;
    }

    public function setFromSpecies(?Pokemon $fromSpecies): static
    {
        $this->fromSpecies = $fromSpecies;

        return $this;
    }

    public function getToSpecies(): ?Pokemon
    {
        return $this->toSpecies;
    }

    public function setToSpecies(?Pokemon $toSpecies): static
    {
        $this->toSpecies = $toSpecies;

        return $this;
    }

    public function getEvolutionTrigger(): ?EvolutionTrigger
    {
        return $this->evolutionTrigger;
    }

    public function setEvolutionTrigger(?EvolutionTrigger $evolutionTrigger): static
    {
        $this->evolutionTrigger = $evolutionTrigger;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }

    public function getMinLevel(): ?int
    {
        return $this->minLevel;
    }

    public function setMinLevel(?int $minLevel): static
    {
        $this->minLevel = $minLevel;

        return $this;
    }

    public function getRequiredRegionForm(): ?RegionForm
    {
        return $this->requiredRegionForm;
    }

    public function setRequiredRegionForm(?RegionForm $requiredRegionForm): static
    {
        $this->requiredRegionForm = $requiredRegionForm;

        return $this;
    }
}
