<?php

namespace App\Entity\Pokedex;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;

#[Api\ApiResource]
#[ORM\Entity]
class RegionForm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PokemonSpecies $species = null;

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

    public function getSpecies(): ?PokemonSpecies
    {
        return $this->species;
    }

    public function setSpecies(?PokemonSpecies $species): self
    {
        $this->species = $species;
        return $this;
    }
}
