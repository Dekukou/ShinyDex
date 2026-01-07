<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class Attack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'attacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    #[ORM\OneToMany(mappedBy: 'attack', targetEntity: PokemonAttackLevel::class, orphanRemoval: true)]
    private Collection $learnedByLevel;

    #[ORM\OneToMany(mappedBy: 'attack', targetEntity: PokemonAttackMachine::class, orphanRemoval: true)]
    private Collection $learnedByMachine;

    public function __construct()
    {
        $this->learnedByLevel = new ArrayCollection();
        $this->learnedByMachine = new ArrayCollection();
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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;
        return $this;
    }
}
