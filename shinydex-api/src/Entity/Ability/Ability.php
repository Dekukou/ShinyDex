<?php

namespace App\Entity\Ability;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class Ability
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, unique: true)]
    private string $apiName;

    #[ORM\Column(length: 150)]
    private string $nameFr;

    #[ORM\Column(length: 150)]
    private string $nameEn;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'ability', targetEntity: PokemonAbility::class, orphanRemoval: true)]
    private Collection $pokemonAbilities;

    public function __construct()
    {
        $this->pokemonAbilities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiName(): string
    {
        return $this->apiName;
    }

    public function setApiName(string $apiName): self
    {
        $this->apiName = $apiName;
        return $this;
    }

    public function getNameFr(): string
    {
        return $this->nameFr;
    }

    public function setNameFr(string $nameFr): self
    {
        $this->nameFr = $nameFr;
        return $this;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    public function setNameEn(string $nameEn): self
    {
        $this->nameEn = $nameEn;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /** @return Collection<int, PokemonAbility> */
    public function getPokemonAbilities(): Collection
    {
        return $this->pokemonAbilities;
    }

    public function addPokemonAbility(PokemonAbility $pokemonAbility): self
    {
        if (!$this->pokemonAbilities->contains($pokemonAbility)) {
            $this->pokemonAbilities->add($pokemonAbility);
            $pokemonAbility->setAbility($this);
        }
        return $this;
    }

    public function removePokemonAbility(PokemonAbility $pokemonAbility): self
    {
        if ($this->pokemonAbilities->removeElement($pokemonAbility)) {
            if ($pokemonAbility->getAbility() === $this) {
                $pokemonAbility->setAbility(null);
            }
        }
        return $this;
    }
}
