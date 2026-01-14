<?php

namespace App\Dto\CaptureHistory;

final class CaptureHistoryCreateDto
{
    public int $pokemonId;
    public int $gameId;
    public int $ballId;
    public int $huntMethodId;
    public bool $isShiny;
    public ?string $gender = null;
    public ?string $formKey = null;
    public ?int $level = null;
    public ?bool $isAlpha = null;
    public ?string $notes = null;
}
