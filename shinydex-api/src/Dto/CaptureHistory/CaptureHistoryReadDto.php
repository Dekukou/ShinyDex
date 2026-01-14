<?php

namespace App\Dto\CaptureHistory;

final class CaptureHistoryReadDto
{
    public int $id;

    public int $pokemonId;
    public string $pokemonName;
    public string $pokemonSprite;

    public bool $isShiny;
    public ?string $gender;
    public ?string $formKey;
    public ?int $level;
    public ?bool $isAlpha;

    public string $game;
    public string $ball;
    public string $huntMethod;

    public \DateTimeImmutable $capturedAt;
    public ?string $notes;
}
