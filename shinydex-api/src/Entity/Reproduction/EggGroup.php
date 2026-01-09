<?php

namespace App\Entity\Reproduction;

use ApiPlatform\Metadata as Api;
use App\Entity\Pokedex\Pokemon;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Reproduction\PokemonEggGroup;

#[Api\ApiResource]
#[ORM\Entity]
class EggGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $apiName;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\ManyToMany(targetEntity: Pokemon::class, mappedBy: 'eggGroups')]
    private Collection $pokemons;


    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiName(): string
    {
        return $this->apiName;
    }

    public function setApiName(string $apiName): self
    {
        $this->apiName = $apiName;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        $this->pokemons->removeElement($pokemon);
        return $this;
    }
}
