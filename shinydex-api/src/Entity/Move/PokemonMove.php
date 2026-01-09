<?php

namespace App\Entity\Move;

use App\Entity\Fight\Attack;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\VersionGroup;
use App\Repository\Move\PokemonMoveRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonMoveRepository::class)]
#[ORM\Table(name: 'pokemon_move')]
#[ORM\UniqueConstraint(
    name: 'uniq_pokemon_move',
    columns: [
        'pokemon_id',
        'move_id',
        'version_group_id',
        'learn_method',
        'level'
    ]
)]
class PokemonMove
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    // ==========================================
    // RELATIONS
    // ==========================================

    #[ORM\ManyToOne(targetEntity: Pokemon::class, inversedBy: 'pokemonMoves')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(targetEntity: Attack::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Attack $move = null;

    #[ORM\ManyToOne(targetEntity: VersionGroup::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?VersionGroup $versionGroup = null;

    // ==========================================
    // DATA
    // ==========================================

    /**
     * level-up | machine | tutor | egg
     */
    #[ORM\Column(type: 'string', length: 50)]
    private string $learnMethod;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $level = null;

    // ==========================================
    // GETTERS / SETTERS
    // ==========================================

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

    public function getMove(): ?Attack
    {
        return $this->move;
    }

    public function setMove(Attack $move): self
    {
        $this->move = $move;
        return $this;
    }

    public function getVersionGroup(): ?VersionGroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }

    public function getLearnMethod(): string
    {
        return $this->learnMethod;
    }

    public function setLearnMethod(string $learnMethod): self
    {
        $this->learnMethod = $learnMethod;
        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;
        return $this;
    }
}
