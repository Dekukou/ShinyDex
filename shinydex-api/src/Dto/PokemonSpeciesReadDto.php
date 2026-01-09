<?php

namespace App\Dto;

class PokemonSpeciesReadDto
{
    public int $id;
    public int $dexNumber;
    public string $nameFr;
    public string $nameEn;
    public string $generation;
    public string $sprite;

    /** @var string[] */
    public array $types = [];

    /** @var PokemonFormDto[] */
    public array $forms = [];
}
