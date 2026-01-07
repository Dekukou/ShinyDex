<?php

namespace App\Entity\Pokedex;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Pokedex\Pokemon;

#[Api\ApiResource]
#[ORM\Entity]
class PokemonSpecies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    private ?int $pokedexNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $nameEn = null;

    #[ORM\Column(length: 255)]
    private ?string $nameFr = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptionFr = null;

    #[ORM\Column]
    private bool $isLegendary = false;

    #[ORM\Column]
    private bool $isMythical = false;

    #[ORM\Column]
    private bool $hasGenderDifference = false;

    #[ORM\Column(nullable: true)]
    private ?int $captureRate = null;

    #[ORM\Column(nullable: true)]
    private ?int $baseHappiness = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Generation $generation = null;

    #[ORM\OneToMany(mappedBy: 'species', targetEntity: Pokemon::class, orphanRemoval: true)]
    private Collection $pokemons;


    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokedexNumber(): ?int
    {
        return $this->pokedexNumber;
    }

    public function setPokedexNumber(int $pokedexNumber): static
    {
        $this->pokedexNumber = $pokedexNumber;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(string $nameEn): static
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    public function getNameFr(): ?string
    {
        return $this->nameFr;
    }

    public function setNameFr(string $nameFr): static
    {
        $this->nameFr = $nameFr;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDescriptionFr(): ?string
    {
        return $this->descriptionFr;
    }

    public function setDescriptionFr(?string $descriptionFr): self
    {
        $this->descriptionFr = $descriptionFr;
        return $this;
    }

    public function getIsLegendary(): bool
    {
        return $this->isLegendary;
    }

    public function setIsLegendary(bool $isLegendary): self
    {
        $this->isLegendary = $isLegendary;

        return $this;
    }

    public function getIsMythical(): bool
    {
        return $this->isMythical;
    }

    public function setIsMythical(bool $isMythical): self
    {
        $this->isMythical = $isMythical;

        return $this;
    }

    public function getHasGenderDifference(): bool
    {
        return $this->hasGenderDifference;
    }

    public function setHasGenderDifference(bool $hasGenderDifference): self
    {
        $this->hasGenderDifference = $hasGenderDifference;

        return $this;
    }

    public function getCaptureRate(): ?int
    {
        return $this->captureRate;
    }

    public function setCaptureRate(?int $captureRate): self
    {
        $this->captureRate = $captureRate;
        return $this;
    }

    public function getBaseHappiness(): ?int
    {
        return $this->baseHappiness;
    }

    public function setBaseHappiness(?int $baseHappiness): self
    {
        $this->baseHappiness = $baseHappiness;
        return $this;
    }

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): static
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * @return Collection<int, Pokemon>
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }


    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons->add($pokemon);
            $pokemon->setSpecies($this);
        }
        return $this;
    }


    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
        }

        return $this;
    }
}
