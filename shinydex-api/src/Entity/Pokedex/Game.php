<?php

namespace App\Entity\Pokedex;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\Pokedex\GameCollectionProvider;
use App\Dto\Pokedex\GameReadDto;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Capture\PokemonCaptureHistory;
use App\Entity\Capture\HuntSession;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/games',
            provider: GameCollectionProvider::class,
            output: GameReadDto::class
        )
    ]
)]
#[ORM\Entity]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private VersionGroup $versionGroup;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Generation $generation = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: PokemonCaptureHistory::class)]
    private Collection $captureHistories;

    #[ORM\OneToMany(
        mappedBy: 'game',
        targetEntity: HuntSession::class
    )]
    private Collection $huntSessions;

    public function __construct()
    {
        $this->captureHistories = new ArrayCollection();
        $this->huntSessions = new ArrayCollection();
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

    public function getVersionGroup(): ?VersionGroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(?VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;
        return $this;
    }

    public function getGeneration(): Generation
    {
        return $this->generation;
    }

    public function setGeneration(Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }
}
