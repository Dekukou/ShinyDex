<?php

namespace App\Entity\Pokedex;

use App\Entity\Pokedex\Game;
use App\Entity\Pokedex\Generation;
use App\Entity\Fight\Machine;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class VersionGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $apiName;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'versionGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private Generation $generation;

    #[ORM\OneToMany(mappedBy: 'versionGroup', targetEntity: Game::class)]
    private Collection $games;

    #[ORM\OneToMany(mappedBy: 'versionGroup', targetEntity: Machine::class)]
    private Collection $machines;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->machines = new ArrayCollection();
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setVersionGroup($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            if ($game->getVersionGroup() === $this) {
                $game->setVersionGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Machine>
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines->add($machine);
            $machine->setVersionGroup($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            if ($machine->getVersionGroup() === $this) {
                $machine->setVersionGroup(null);
            }
        }

        return $this;
    }
}
