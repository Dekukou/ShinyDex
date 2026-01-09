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
    private string $apiName;

    #[ORM\Column(length: 150)]
    private string $name;

    #[ORM\Column(nullable: true)]
    private ?int $power = null;

    #[ORM\Column(nullable: true)]
    private ?int $accuracy = null;

    #[ORM\Column(nullable: true)]
    private ?int $pp = null;

    #[ORM\Column(length: 20)]
    private string $damageClass;
    // physical | special | status

    #[ORM\Column(nullable: true)]
    private ?int $priority = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'attacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): self
    {
        $this->power = $power;
        return $this;
    }

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(?int $accuracy): self
    {
        $this->accuracy = $accuracy;
        return $this;
    }

    public function getPp(): ?int
    {
        return $this->pp;
    }

    public function setPp(?int $pp): self
    {
        $this->pp = $pp;
        return $this;
    }

    public function getDamageClass(): string
    {
        return $this->damageClass;
    }

    public function setDamageClass(string $damageClass): self
    {
        if (!in_array($damageClass, ['physical', 'special', 'status'], true)) {
            throw new \InvalidArgumentException('Invalid damage class');
        }

        $this->damageClass = $damageClass;
        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(?int $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }
}
