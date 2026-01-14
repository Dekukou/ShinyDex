<?php

namespace App\Entity\Capture;

use ApiPlatform\Metadata as Api;
use App\Dto\CaptureHistory\CaptureHistoryCreateDto;
use App\Dto\CaptureHistory\CaptureHistoryReadDto;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Pokedex\Pokemon;
use App\Entity\User\User;
use App\Entity\Pokedex\Game;
use App\State\CaptureHistory\CaptureHistoryCreateProcessor;
use App\State\CaptureHistory\CaptureHistoryCollectionProvider;

#[Api\ApiResource(
    operations: [
        new Api\GetCollection(
            output: CaptureHistoryReadDto::class,
            provider: CaptureHistoryCollectionProvider::class,
            security: "is_granted('ROLE_USER')"
        ),
        new Api\Post(
            input: CaptureHistoryCreateDto::class,
            output: CaptureHistoryReadDto::class,
            processor: CaptureHistoryCreateProcessor::class,
            security: "is_granted('ROLE_USER')"
        )
    ]
)]
#[ORM\Entity]
class PokemonCaptureHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'captureHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ball $ball = null;

    #[ORM\ManyToOne(inversedBy: 'captures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?HuntMethod $huntMethod = null;

    /**
     * Shiny ou non
     */
    #[ORM\Column]
    private bool $isShiny = false;

    /**
     * Genre du Pokémon capturé
     * male / female / null
     */
    #[ORM\Column(length: 10, nullable: true)]
    private ?string $gender = null;

    /**
     * Clé de forme capturée (future-proof)
     * null = forme par défaut
     */
    #[ORM\Column(length: 50, nullable: true)]
    private ?string $formKey = null;

    #[ORM\Column(nullable: true)]
    private ?int $level = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isAlpha = null; // Legends Arceus

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $capturedAt;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->capturedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;
        return $this;
    }

    public function getBall(): ?Ball
    {
        return $this->ball;
    }

    public function setBall(?Ball $ball): self
    {
        $this->ball = $ball;
        return $this;
    }

    public function getHuntMethod(): ?HuntMethod
    {
        return $this->huntMethod;
    }

    public function setHuntMethod(?HuntMethod $huntMethod): self
    {
        $this->huntMethod = $huntMethod;
        return $this;
    }

    public function isShiny(): bool
    {
        return $this->isShiny;
    }

    public function setIsShiny(bool $isShiny): self
    {
        $this->isShiny = $isShiny;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;
        return $this;
    }

    public function getFormKey(): ?string
    {
        return $this->formKey;
    }

    public function setFormKey(?string $formKey): self
    {
        $this->formKey = $formKey;
        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;
        return $this;
    }

    public function isAlpha(): ?bool
    {
        return $this->isAlpha;
    }

    public function setIsAlpha(?bool $isAlpha): self
    {
        $this->isAlpha = $isAlpha;
        return $this;
    }

    public function getCapturedAt(): \DateTimeImmutable
    {
        return $this->capturedAt;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }
}
