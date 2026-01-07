<?php

namespace App\Entity\Evolution;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;

#[Api\ApiResource]
#[ORM\Entity]
class PokemonEvolution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'evolutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $fromPokemon = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $toPokemon = null;

    #[ORM\ManyToOne(inversedBy: 'evolutions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EvolutionTrigger $trigger = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Item $requiredItem = null;

    #[ORM\Column(nullable: true)]
    private ?int $minLevel = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tradeRequired = null;

    #[ORM\Column(nullable: true)]
    private ?string $extraCondition = null; // Friendship, time of day, region...

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFromPokemon(): ?Pokemon
    {
        return $this->fromPokemon;
    }

    public function setFromPokemon(?Pokemon $fromPokemon): self
    {
        $this->fromPokemon = $fromPokemon;
        return $this;
    }

    public function getToPokemon(): ?Pokemon
    {
        return $this->toPokemon;
    }

    public function setToPokemon(?Pokemon $toPokemon): self
    {
        $this->toPokemon = $toPokemon;
        return $this;
    }

    public function getTrigger(): ?EvolutionTrigger
    {
        return $this->trigger;
    }

    public function setTrigger(?EvolutionTrigger $trigger): self
    {
        $this->trigger = $trigger;
        return $this;
    }

    public function getRequiredItem(): ?Item
    {
        return $this->requiredItem;
    }

    public function setRequiredItem(?Item $requiredItem): self
    {
        $this->requiredItem = $requiredItem;
        return $this;
    }
}
