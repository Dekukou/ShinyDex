<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\State\PokemonMove\PokemonMoveCollectionProvider;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/pokemon/{id}/moves',
            provider: PokemonMoveCollectionProvider::class,
            paginationItemsPerPage: 20
        )
    ]
)]
class PokemonMoveReadDto
{
    public int $id;
    public string $name;
    public string $type;

    public ?int $power = null;
    public ?int $accuracy = null;
    public ?int $pp = null;

    public string $damageClass;
    public string $description;

    public string $learnMethod;
    public ?int $level;
    public string $versionGroup;
}
