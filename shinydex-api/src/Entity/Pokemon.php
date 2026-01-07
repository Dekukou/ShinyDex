<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[ApiResource]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?PokemonSpecies $species = null;

    #[ORM\Column]
    private ?bool $isDefaultForm = null;

    #[ORM\Column]
    private ?int $boxOrder = null;

    #[ORM\ManyToOne]
    private ?RegionForm $regionForm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecies(): ?PokemonSpecies
    {
        return $this->species;
    }

    public function setSpecies(?PokemonSpecies $species): static
    {
        $this->species = $species;

        return $this;
    }

    public function isDefaultForm(): ?bool
    {
        return $this->isDefaultForm;
    }

    public function setIsDefaultForm(bool $isDefaultForm): static
    {
        $this->isDefaultForm = $isDefaultForm;

        return $this;
    }

    public function getBoxOrder(): ?int
    {
        return $this->boxOrder;
    }

    public function setBoxOrder(int $boxOrder): static
    {
        $this->boxOrder = $boxOrder;

        return $this;
    }

    public function getRegionForm(): ?RegionForm
    {
        return $this->regionForm;
    }

    public function setRegionForm(?RegionForm $regionForm): static
    {
        $this->regionForm = $regionForm;

        return $this;
    }
}
