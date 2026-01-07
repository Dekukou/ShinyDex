<?php

namespace App\Entity\Pokedex;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use App\Entity\Fight\PokemonType;
use App\Entity\Ability\PokemonAbility;
use App\Entity\Reproduction\PokemonEggGroup;
use App\Entity\Evolution\PokemonFamily;
use App\Entity\Evolution\PokemonEvolution;
use App\Entity\Capture\PokemonCaptureHistory;
use App\Entity\Sprite\PokemonSprite;
use App\Entity\Fight\PokemonAttackLevel;
use App\Entity\Fight\PokemonAttackMachine;

#[Api\ApiResource]
#[ORM\Entity]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Numéro national du Pokédex
     */
    #[ORM\Column]
    private int $dexNumber;

    /**
     * Nom affiché
     */
    #[ORM\Column(length: 150)]
    private string $name;

    /**
     * Espèce (Bulbasaur, Charmander…)
     */
    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PokemonSpecies $species = null;

    /**
     * Génération d’introduction
     */
    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Generation $generation = null;

    /**
     * Types du Pokémon
     */
    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonType::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $types;

    /**
     * Talents du Pokémon
     */
    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonAbility::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $abilities;

    /**
     * Groupes d’œufs
     */
    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonEggGroup::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $eggGroups;

    /**
     * Famille d’évolution
     */
    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: true)]
    private ?PokemonFamily $family = null;

    /**
     * Évolutions sortantes
     */
    #[ORM\OneToMany(mappedBy: 'fromPokemon', targetEntity: PokemonEvolution::class)]
    private Collection $evolutions;

    /**
     * Historique de capture
     */
    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonCaptureHistory::class)]
    private Collection $captures;

    /**
     * Sprites (forme par défaut pour l’instant)
     */
    #[ORM\OneToOne(mappedBy: 'pokemon', targetEntity: PokemonSprite::class, cascade: ['persist', 'remove'])]
    private ?PokemonSprite $sprite = null;

    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonAttackLevel::class)]
    private Collection $levelAttacks;

    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonAttackMachine::class)]
    private Collection $machineAttacks;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->abilities = new ArrayCollection();
        $this->eggGroups = new ArrayCollection();
        $this->evolutions = new ArrayCollection();
        $this->captures = new ArrayCollection();
        $this->levelAttacks = new ArrayCollection();
        $this->machineAttacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDexNumber(): int
    {
        return $this->dexNumber;
    }

    public function setDexNumber(int $dexNumber): self
    {
        $this->dexNumber = $dexNumber;
        return $this;
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

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;
        return $this;
    }

    /** @return Collection<int, PokemonType> */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(PokemonType $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
            $type->setPokemon($this);
        }
        return $this;
    }

    public function removeType(PokemonType $type): self
    {
        if ($this->types->removeElement($type)) {
            if ($type->getPokemon() === $this) {
                $type->setPokemon(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, PokemonAbility> */
    public function getAbilities(): Collection
    {
        return $this->abilities;
    }

    public function addAbility(PokemonAbility $ability): self
    {
        if (!$this->abilities->contains($ability)) {
            $this->abilities->add($ability);
            $ability->setPokemon($this);
        }
        return $this;
    }

    public function removeAbility(PokemonAbility $ability): self
    {
        if ($this->abilities->removeElement($ability)) {
            if ($ability->getPokemon() === $this) {
                $ability->setPokemon(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, PokemonEggGroup> */
    public function getEggGroups(): Collection
    {
        return $this->eggGroups;
    }

    public function addEggGroup(PokemonEggGroup $eggGroup): self
    {
        if (!$this->eggGroups->contains($eggGroup)) {
            $this->eggGroups->add($eggGroup);
            $eggGroup->setPokemon($this);
        }
        return $this;
    }

    public function removeEggGroup(PokemonEggGroup $eggGroup): self
    {
        if ($this->eggGroups->removeElement($eggGroup)) {
            if ($eggGroup->getPokemon() === $this) {
                $eggGroup->setPokemon(null);
            }
        }
        return $this;
    }

    public function getFamily(): ?PokemonFamily
    {
        return $this->family;
    }

    public function setFamily(?PokemonFamily $family): self
    {
        $this->family = $family;
        return $this;
    }

    /** @return Collection<int, PokemonEvolution> */
    public function getEvolutions(): Collection
    {
        return $this->evolutions;
    }

    /** @return Collection<int, PokemonCaptureHistory> */
    public function getCaptures(): Collection
    {
        return $this->captures;
    }

    public function getSprite(): ?PokemonSprite
    {
        return $this->sprite;
    }

    public function setSprite(?PokemonSprite $sprite): self
    {
        $this->sprite = $sprite;
        return $this;
    }
}
