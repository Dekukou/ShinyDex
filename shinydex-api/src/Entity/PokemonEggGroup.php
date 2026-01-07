<?php

namespace App\Entity;

use App\Repository\PokemonEggGroupRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonEggGroupRepository::class)]
class PokemonEggGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PokemonSpecies $species = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EggGroup $eggGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecies(): ?PokemonSpecies
    {
        return $this->species;
    }

    public function setSpecies(?PokemonSpecies $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function getEggGroup(): ?EggGroup
    {
        return $this->eggGroup;
    }

    public function setEggGroup(?EggGroup $eggGroup): static
    {
        $this->eggGroup = $eggGroup;

        return $this;
    }
}
