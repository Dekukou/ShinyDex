<?php

namespace App\Dto\Hunt;

class HuntReadDto
{
    public int $id;

    public int $pokemonId;
    public string $pokemonName;
    public string $sprite;

    public string $method;
    public int $counter;

    public bool $isShinyFound;
    public ?\DateTimeInterface $startedAt;
    public ?\DateTimeInterface $endedAt;
}
