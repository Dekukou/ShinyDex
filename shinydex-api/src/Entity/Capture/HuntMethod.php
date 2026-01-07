<?php

namespace App\Entity\Capture;

use ApiPlatform\Metadata as Api;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[Api\ApiResource]
#[ORM\Entity]
class HuntMethod
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150, unique: true)]
    private string $name; // Masuda, Reset, Chain Fishing, Sandwich...

    #[ORM\OneToMany(mappedBy: 'huntMethod', targetEntity: PokemonCaptureHistory::class)]
    private Collection $captures;

    #[ORM\OneToMany(mappedBy: 'huntMethod', targetEntity: HuntSession::class)]
    private Collection $sessions;

    public function __construct()
    {
        $this->captures = new ArrayCollection();
        $this->sessions = new ArrayCollection();
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
}
