<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HuntSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HuntSessionRepository::class)]
#[ApiResource]
class HuntSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?HuntMethod $huntMethod = null;

    #[ORM\Column]
    private ?int $encounters = null;

    #[ORM\Column]
    private ?bool $success = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): static
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getHuntMethod(): ?HuntMethod
    {
        return $this->huntMethod;
    }

    public function setHuntMethod(?HuntMethod $huntMethod): static
    {
        $this->huntMethod = $huntMethod;

        return $this;
    }

    public function getEncounters(): ?int
    {
        return $this->encounters;
    }

    public function setEncounters(int $encounters): static
    {
        $this->encounters = $encounters;

        return $this;
    }

    public function isSuccess(): ?bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): static
    {
        $this->success = $success;

        return $this;
    }
}
