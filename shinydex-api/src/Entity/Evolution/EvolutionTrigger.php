<?php

namespace App\Entity\Evolution;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class EvolutionTrigger
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $name; // Level, Item, Trade, Friendship...

    #[ORM\OneToMany(mappedBy: 'trigger', targetEntity: PokemonEvolution::class)]
    private Collection $evolutions;

    public function __construct()
    {
        $this->evolutions = new ArrayCollection();
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

    /** @return Collection<int, PokemonEvolution> */
    public function getEvolutions(): Collection
    {
        return $this->evolutions;
    }
}
