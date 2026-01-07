<?php

namespace App\Entity\Reproduction;

use ApiPlatform\Metadata as Api;
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
    private string $name;

    #[ORM\OneToMany(mappedBy: 'eggGroup', targetEntity: PokemonEggGroup::class, orphanRemoval: true)]
    private Collection $pokemonEggGroups;

    public function __construct()
    {
        $this->pokemonEggGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /** @return Collection<int, PokemonEggGroup> */
    public function getPokemonEggGroups(): Collection
    {
        return $this->pokemonEggGroups;
    }

    public function addPokemonEggGroup(PokemonEggGroup $pokemonEggGroup): self
    {
        if (!$this->pokemonEggGroups->contains($pokemonEggGroup)) {
            $this->pokemonEggGroups->add($pokemonEggGroup);
            $pokemonEggGroup->setEggGroup($this);
        }
        return $this;
    }

    public function removePokemonEggGroup(PokemonEggGroup $pokemonEggGroup): self
    {
        if ($this->pokemonEggGroups->removeElement($pokemonEggGroup)) {
            if ($pokemonEggGroup->getEggGroup() === $this) {
                $pokemonEggGroup->setEggGroup(null);
            }
        }
        return $this;
    }
}
