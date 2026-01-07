<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Pokedex\Generation;

#[Api\ApiResource]
#[ORM\Entity]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $code; // CT01, CS02, TM100...

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Generation $generation = null;

    #[ORM\OneToMany(mappedBy: 'machine', targetEntity: PokemonAttackMachine::class, orphanRemoval: true)]
    private Collection $pokemonAttacks;

    public function __construct()
    {
        $this->pokemonAttacks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
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
}
