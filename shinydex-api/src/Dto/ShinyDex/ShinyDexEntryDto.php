<?php

namespace App\Dto\ShinyDex;

final class ShinyDexEntryDto
{
    public int $pokedexNumber;
    public string $name;
    public string $sprite;
    public bool $isShinyCaptured;

    // pour les formes
    public ?string $formType; // alola / galar / hisui / gender
    public ?string $formKey;
}
