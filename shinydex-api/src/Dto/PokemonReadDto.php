<?php

namespace App\Dto;

class PokemonReadDto
{
    public int $id;
    public string $nameFr;
    public string $nameEn;
    public int $dexNumber;
    public array $types = [];
    public array $abilities = [];
    public array $stats = [];

    public array $sprites = [
        'default' => null,
        'female' => null,
        'shiny' => null,
        'shiny_female' => null,
    ];
}
