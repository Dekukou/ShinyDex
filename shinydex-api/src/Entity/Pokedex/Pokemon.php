<?php

namespace App\Entity\Pokedex;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\PokemonReadDto;
use App\Entity\Evolution\PokemonEvolution;
use App\Entity\Evolution\PokemonFamily;
use App\Entity\Ability\PokemonAbility;
use App\Entity\Capture\PokemonCaptureHistory;
use App\Entity\Fight\PokemonType;
use App\Entity\Move\PokemonMove;
use App\Entity\Pokedex\RegionForm;
use App\Entity\Reproduction\EggGroup;
use App\Entity\Sprite\PokemonSprite;
use App\State\PokemonCollectionProvider;
use App\State\PokemonItemProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(
            provider: PokemonItemProvider::class,
            output: PokemonReadDto::class
        ),
        new GetCollection(
            provider: PokemonCollectionProvider::class,
            output: PokemonReadDto::class,
        )
    ]
)]
#[ORM\Entity]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // ==================================================
    // CORE
    // ==================================================

    #[ORM\Column(length: 150, unique: true)]
    private string $formKey;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $nameFr = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $nameEn = null;

    #[ORM\Column()]
    private bool $isDefault = true;

    #[ORM\Column()]
    private bool $isMega = false;

    #[ORM\Column()]
    private bool $isGmax = false;

    // ==================================================
    // STATS (PER FORM)
    // ==================================================

    #[ORM\Column(nullable: true)]
    private ?int $hp = null;

    #[ORM\Column(nullable: true)]
    private ?int $attack = null;

    #[ORM\Column(nullable: true)]
    private ?int $defense = null;

    #[ORM\Column(nullable: true)]
    private ?int $specialAttack = null;

    #[ORM\Column(nullable: true)]
    private ?int $specialDefense = null;

    #[ORM\Column(nullable: true)]
    private ?int $speed = null;

    // ==================================================
    // RELATIONS
    // ==================================================

    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PokemonSpecies $species = null;

    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonType::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['slot' => 'ASC'])]
    private Collection $types;

    #[ORM\OneToOne(mappedBy: 'pokemon', targetEntity: PokemonSprite::class, cascade: ['persist', 'remove'])]
    private ?PokemonSprite $sprite = null;

    #[ORM\ManyToOne(inversedBy: 'members')]
    private ?PokemonFamily $family = null;

    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonAbility::class)]
    #[ORM\OrderBy(['slot' => 'ASC'])]
    private Collection $abilities;

    #[ORM\OneToMany(mappedBy: 'fromPokemon', targetEntity: PokemonEvolution::class)]
    private Collection $evolutions;

    #[ORM\OneToMany(
        mappedBy: 'pokemon',
        targetEntity: PokemonMove::class,
        orphanRemoval: true
    )]
    private Collection $pokemonMoves;

    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    private ?Generation $generation = null;

    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    private ?RegionForm $regionForm = null;

    #[ORM\ManyToMany(targetEntity: EggGroup::class, inversedBy: 'pokemons')]
    #[ORM\JoinTable(name: 'pokemon_egg_group')]
    private Collection $eggGroups;

    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: PokemonCaptureHistory::class)]
    private Collection $captures;

    public function __construct()
    {
        $this->types = new ArrayCollection();
        $this->abilities = new ArrayCollection();
        $this->evolutions = new ArrayCollection();
        $this->eggGroups = new ArrayCollection();
        $this->captures = new ArrayCollection();
    }

    // ==================================================
    // GETTERS / SETTERS
    // ==================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormKey(): string
    {
        return $this->formKey;
    }

    public function setFormKey(string $formKey): self
    {
        $this->formKey = $formKey;
        return $this;
    }

    public function getNameFr(): ?string
    {
        return $this->nameFr;
    }

    public function setNameFr(?string $nameFr): self
    {
        $this->nameFr = $nameFr;
        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->nameEn;
    }

    public function setNameEn(?string $nameEn): self
    {
        $this->nameEn = $nameEn;
        return $this;
    }

    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function isMega(): bool
    {
        return $this->isMega;
    }

    public function setIsMega(bool $isMega): self
    {
        $this->isMega = $isMega;

        return $this;
    }

    public function isGmax(): bool
    {
        return $this->isGmax;
    }

    public function setIsGmax(bool $isGmax): self
    {
        $this->isGmax = $isGmax;

        return $this;
    }

    // ==================================================
    // STATS GETTERS / SETTERS
    // ==================================================

    public function getHp(): ?int
    {
        return $this->hp;
    }

    public function setHp(?int $hp): self
    {
        $this->hp = $hp;
        return $this;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(?int $attack): self
    {
        $this->attack = $attack;
        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(?int $defense): self
    {
        $this->defense = $defense;
        return $this;
    }

    public function getSpecialAttack(): ?int
    {
        return $this->specialAttack;
    }

    public function setSpecialAttack(?int $specialAttack): self
    {
        $this->specialAttack = $specialAttack;
        return $this;
    }

    public function getSpecialDefense(): ?int
    {
        return $this->specialDefense;
    }

    public function setSpecialDefense(?int $specialDefense): self
    {
        $this->specialDefense = $specialDefense;
        return $this;
    }

    public function getSpeed(): ?int
    {
        return $this->speed;
    }

    public function setSpeed(?int $speed): self
    {
        $this->speed = $speed;
        return $this;
    }

    // ==================================================
    // RELATIONS
    // ==================================================

    public function getSpecies(): ?PokemonSpecies
    {
        return $this->species;
    }

    public function setSpecies(PokemonSpecies $species): self
    {
        $this->species = $species;
        return $this;
    }

    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(PokemonType $pokemonType): self
    {
        if (!$this->types->contains($pokemonType)) {
            $this->types->add($pokemonType);
            $pokemonType->setPokemon($this);
        }

        return $this;
    }

    public function removeType(PokemonType $pokemonType): self
    {
        if ($this->types->removeElement($pokemonType)) {
            if ($pokemonType->getPokemon() === $this) {
                $pokemonType->setPokemon(null);
            }
        }

        return $this;
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

    public function getFamily(): ?PokemonFamily
    {
        return $this->family;
    }

    public function setFamily(?PokemonFamily $family): self
    {
        $this->family = $family;
        return $this;
    }

    public function getAbilities(): Collection
    {
        return $this->abilities;
    }

    // ? Evolutions ?

    public function getPokemonMoves(): Collection
    {
        return $this->pokemonMoves;
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

    public function getRegionForm(): ?RegionForm
    {
        return $this->regionForm;
    }

    public function setRegionForm(?RegionForm $regionForm): self
    {
        $this->regionForm = $regionForm;
        return $this;
    }

    public function getEggGroups(): Collection
    {
        return $this->eggGroups;
    }

    public function addEggGroup(EggGroup $eggGroup): self
    {
        if (!$this->eggGroups->contains($eggGroup)) {
            $this->eggGroups->add($eggGroup);
            $eggGroup->addPokemon($this);
        }

        return $this;
    }

    public function removeEggGroup(EggGroup $eggGroup): self
    {
        if ($this->eggGroups->removeElement($eggGroup)) {
            $eggGroup->removePokemon($this);
        }
        return $this;
    }

    // ? Capture ?
}
