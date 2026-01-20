<?php

namespace App\Dto\ShinyDex;

final class ShinyDexReadDto
{
    /** @var ShinyDexBoxDto[] */
    public array $boxes = [];

    public int $totalBasePokemon;     // 1025
    public ?int $totalBaseShiny = 0;       // sans formes
    public ?int $totalGlobalShiny = 0;     // avec formes

    public int $totalBoxes = 0;
    public bool $hasMore = false;
}
