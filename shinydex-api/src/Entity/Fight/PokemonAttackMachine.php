<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;

#[Api\ApiResource]
#[ORM\Entity]
#[ORM\UniqueConstraint(name: 'uniq_pokemon_machine_attack', columns: ['pokemon_id', 'machine_id'])]
class PokemonAttackMachine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'machineAttacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'learnedByMachine')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attack $attack = null;

    #[ORM\ManyToOne(inversedBy: 'pokemonAttacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Machine $machine = null;

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

    public function getAttack(): ?Attack
    {
        return $this->attack;
    }

    public function setAttack(?Attack $attack): self
    {
        $this->attack = $attack;
        return $this;
    }

    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    public function setMachine(?Machine $machine): self
    {
        $this->machine = $machine;
        return $this;
    }
}
