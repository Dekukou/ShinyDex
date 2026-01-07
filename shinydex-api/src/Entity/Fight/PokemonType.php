<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;

#[Api\ApiResource]
#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'uniq_pokemon_type', columns: ['pokemon_id', 'type_id'])]
class PokemonType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'types')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    #[ORM\Column]
    private int $slot = 1; // 1 = primary, 2 = secondary

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getSlot(): int
    {
        return $this->slot;
    }

    public function setSlot(int $slot): self
    {
        $this->slot = $slot;
        return $this;
    }
}
