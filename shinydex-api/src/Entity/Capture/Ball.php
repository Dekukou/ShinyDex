<?php

namespace App\Entity\Capture;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class Ball
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $name;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $spritePath = null;

    #[ORM\Column(options: ['default' => true])]
    private bool $isUsable = true;

    #[ORM\Column(options: ['default' => false])]
    private bool $isLegendArceus = false;

    #[ORM\OneToMany(mappedBy: 'ball', targetEntity: PokemonCaptureHistory::class)]
    private Collection $captures;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
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

    public function getSpritePath(): ?string
    {
        return $this->spritePath;
    }

    public function setSpritePath(?string $spritePath): self
    {
        $this->spritePath = $spritePath;
        return $this;
    }

    public function isUsable(): bool
    {
        return $this->isUsable;
    }

    public function setIsUsable(bool $isUsable): self
    {
        $this->isUsable = $isUsable;
        return $this;
    }

    public function isLegendArceus(): bool
    {
        return $this->isLegendArceus;
    }

    public function setIsLegendArceus(bool $isLegendArceus): self
    {
        $this->isLegendArceus = $isLegendArceus;
        return $this;
    }
}
