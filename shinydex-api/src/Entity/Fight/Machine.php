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
    private string $name; // CT01, CS02, TM100...

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Generation $generation = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attack $attack = null;

    #[ORM\OneToMany(mappedBy: 'machine', targetEntity: PokemonAttackMachine::class)]
    private Collection $teaches;

    public function __construct()
    {
        $this->teaches = new ArrayCollection();
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

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;
        return $this;
    }

    public function getAttack(): ?Attack
    {
        return $this->attack;
    }

    public function setAttack(?Attack $attack): self
    {
        $this->attack = $attack;
        return $this;
    }

    /** @return Collection<int, PokemonAttackMachine> */
    public function getTeaches(): Collection
    {
        return $this->teaches;
    }

    public function setTeaches(Collection $teaches): self
    {
        // On détache les anciennes relations
        foreach ($this->teaches as $attackMachine) {
            if (!$teaches->contains($attackMachine)) {
                $attackMachine->setMachine(null);
            }
        }

        // On attache les nouvelles relations
        foreach ($teaches as $attackMachine) {
            $attackMachine->setMachine($this);
        }

        $this->teaches = $teaches;

        return $this;
    }

    public function addTeach(PokemonAttackMachine $attackMachine): self
    {
        if (!$this->teaches->contains($attackMachine)) {
            $this->teaches->add($attackMachine);
            $attackMachine->setMachine($this);
        }

        return $this;
    }
}
