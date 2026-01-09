<?php

namespace App\Entity\Pokedex;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class Generation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $name;

    #[ORM\OneToMany(mappedBy: 'generation', targetEntity: Pokemon::class)]
    private Collection $pokemons;

    #[ORM\OneToMany(mappedBy: 'generation', targetEntity: Game::class)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'generation', targetEntity: VersionGroup::class)]
    private Collection $versionGroups;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->games = new ArrayCollection();
        $this->versionGroups = new ArrayCollection();
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

    /** @return Collection<int, Pokemon> */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    /** @return Collection<int, Game> */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function getVersionGroups(): Collection
    {
        return $this->versionGroups;
    }
}
