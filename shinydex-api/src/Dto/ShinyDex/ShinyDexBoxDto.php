<?php

namespace App\Dto\ShinyDex;

final class ShinyDexBoxDto
{
    public int $boxIndex;        // 1-based
    public string $boxType;      // "national" | "alola" | "galar" | "hisui" | "gender"
    public ?string $label;       // "Box 12" | "Alola Forms"

    /** @var ShinyDexEntryDto[] */
    public array $entries = [];

    public int $total;           // 30 ou moins
    public ?int $shinyCount = 0;      // shinys capturés
    public float $completion;    // %
}
