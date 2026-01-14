<?php

namespace App\Dto\ShinyDex;

final class ShinyDexPaginationDto
{
    public int $page;
    public int $boxesPerPage;
    public int $boxSize;
    public int $totalSpecies;
    public int $totalBoxes;
    public int $totalPages;
}
