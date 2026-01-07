<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;


#[Api\ApiResource]
#[ORM\Entity]
class PokemonAttackLevel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'levelAttacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'learnedByLevel')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attack $attack = null;

    #[ORM\Column]
    private int $level;

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

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;
        return $this;
    }
}
