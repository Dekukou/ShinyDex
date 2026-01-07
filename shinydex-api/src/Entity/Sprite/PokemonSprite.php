<?php

namespace App\Entity\Sprite;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;

#[Api\ApiResource]
#[ORM\Entity]
class PokemonSprite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Pokémon concerné
     * (1 sprite principal par Pokémon pour l’instant)
     */
    #[ORM\OneToOne(inversedBy: 'sprite')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Pokemon $pokemon = null;

    /**
     * Sprite face normal (obligatoire)
     * ex: /sprites/pokemon/025/front.png
     */
    #[ORM\Column(length: 255)]
    private string $frontDefault;

    /**
     * Sprite face normal femelle (si existe)
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frontFemale = null;

    /**
     * Sprite face shiny (obligatoire)
     */
    #[ORM\Column(length: 255)]
    private string $frontShiny;

    /**
     * Sprite face shiny femelle (si existe)
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frontShinyFemale = null;

    /**
     * Clé de forme (future évolution)
     * null = forme par défaut
     * ex: meadow, sandstorm, A, pharaoh...
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $formKey = null;

    /**
     * Source des sprites
     * pokeapi par défaut
     */
    #[ORM\Column(length: 50)]
    private string $source = 'pokeapi';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    public function getFrontDefault(): string
    {
        return $this->frontDefault;
    }

    public function setFrontDefault(string $frontDefault): self
    {
        $this->frontDefault = $frontDefault;
        return $this;
    }

    public function getFrontFemale(): ?string
    {
        return $this->frontFemale;
    }

    public function setFrontFemale(?string $frontFemale): self
    {
        $this->frontFemale = $frontFemale;
        return $this;
    }

    public function getFrontShiny(): string
    {
        return $this->frontShiny;
    }

    public function setFrontShiny(string $frontShiny): self
    {
        $this->frontShiny = $frontShiny;
        return $this;
    }

    public function getFrontShinyFemale(): ?string
    {
        return $this->frontShinyFemale;
    }

    public function setFrontShinyFemale(?string $frontShinyFemale): self
    {
        $this->frontShinyFemale = $frontShinyFemale;
        return $this;
    }

    public function getFormKey(): ?string
    {
        return $this->formKey;
    }

    public function setFormKey(?string $formKey): self
    {
        $this->formKey = $formKey;
        return $this;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }
}
