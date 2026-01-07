<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BallRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BallRepository::class)]
#[ApiResource]
class Ball
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $catchRateBonus = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCatchRateBonus(): ?float
    {
        return $this->catchRateBonus;
    }

    public function setCatchRateBonus(float $catchRateBonus): static
    {
        $this->catchRateBonus = $catchRateBonus;

        return $this;
    }
}
