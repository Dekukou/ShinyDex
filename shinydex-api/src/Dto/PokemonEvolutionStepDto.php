<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\PokemonEvolution\PokemonEvolutionChainProvider;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/pokemon-species/{id}/evolution-chain',
            provider: PokemonEvolutionChainProvider::class,
            paginationItemsPerPage: 20
        )
    ],
)]
class PokemonEvolutionStepDto
{
    public array $from;
    public array $to;

    public string $trigger;
    public ?int $minLevel = null;
    public ?string $item = null;
    public ?bool $tradeRequired = false;
    public ?string $conditions = null;
}
