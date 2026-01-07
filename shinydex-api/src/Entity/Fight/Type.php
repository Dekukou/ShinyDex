<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Fight\PokemonType;
use App\Entity\Fight\Attack;

#[Api\ApiResource]
#[ORM\Entity]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: PokemonType::class, orphanRemoval: true)]
    private Collection $pokemonTypes;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Attack::class)]
    private Collection $attacks;

    public function __construct()
    {
        $this->pokemonTypes = new ArrayCollection();
        $this->attacks = new ArrayCollection();
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

    /** @return Collection<int, PokemonType> */
    public function getPokemonTypes(): Collection
    {
        return $this->pokemonTypes;
    }

    public function addPokemonType(PokemonType $pokemonType): self
    {
        if (!$this->pokemonTypes->contains($pokemonType)) {
            $this->pokemonTypes->add($pokemonType);
            $pokemonType->setType($this);
        }
        return $this;
    }

    public function removePokemonType(PokemonType $pokemonType): self
    {
        if ($this->pokemonTypes->removeElement($pokemonType)) {
            if ($pokemonType->getType() === $this) {
                $pokemonType->setType(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Attack> */
    public function getAttacks(): Collection
    {
        return $this->attacks;
    }
}
