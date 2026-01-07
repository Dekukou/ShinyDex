<?php

namespace App\Entity\Reproduction;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;

#[Api\ApiResource]
#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'uniq_pokemon_egg_group', columns: ['pokemon_id', 'egg_group_id'])]
class PokemonEggGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eggGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonEggGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EggGroup $eggGroup = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    public function getEggGroup(): ?EggGroup
    {
        return $this->eggGroup;
    }

    public function setEggGroup(?EggGroup $eggGroup): self
    {
        $this->eggGroup = $eggGroup;
        return $this;
    }
}
