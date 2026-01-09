<?php

namespace App\Entity\Fight;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\VersionGroup;

#[Api\ApiResource]
#[ORM\Entity]
#[ORM\Table(
    uniqueConstraints: [
        new ORM\UniqueConstraint(
            name: 'uniq_machine_name_vg',
            columns: ['name', 'version_group_id']
        )
    ]
)]
class Machine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $name; // CT01, CS02, TM100...

    #[ORM\ManyToOne(inversedBy: 'machines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VersionGroup $versionGroup = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Attack $attack = null;

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

    public function getAttack(): ?Attack
    {
        return $this->attack;
    }

    public function setAttack(?Attack $attack): self
    {
        $this->attack = $attack;
        return $this;
    }
}
