<?php

namespace App\Entity\Ability;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class Ability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, unique: true)]
    private string $name;

    #[ORM\Column(type: 'text')]
    private string $description;

    #[ORM\OneToMany(mappedBy: 'ability', targetEntity: PokemonAbility::class, orphanRemoval: true)]
    private Collection $pokemonAbilities;

    public function __construct()
    {
        $this->pokemonAbilities = new ArrayCollection();
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /** @return Collection<int, PokemonAbility> */
    public function getPokemonAbilities(): Collection
    {
        return $this->pokemonAbilities;
    }

    public function addPokemonAbility(PokemonAbility $pokemonAbility): self
    {
        if (!$this->pokemonAbilities->contains($pokemonAbility)) {
            $this->pokemonAbilities->add($pokemonAbility);
            $pokemonAbility->setAbility($this);
        }
        return $this;
    }

    public function removePokemonAbility(PokemonAbility $pokemonAbility): self
    {
        if ($this->pokemonAbilities->removeElement($pokemonAbility)) {
            if ($pokemonAbility->getAbility() === $this) {
                $pokemonAbility->setAbility(null);
            }
        }
        return $this;
    }
}
