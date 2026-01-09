<?php

namespace App\Dto;

class PokemonFormDto
{
    public int $id;
    public string $formKey;
    public bool $isDefault;
    public string $name;
    public array $types;
    public array $sprites;
}
