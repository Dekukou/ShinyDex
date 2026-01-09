<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\PokemonReadDto;
use App\Repository\Pokedex\PokemonRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PokemonItemProvider implements ProviderInterface
{
    public function __construct(
        private PokemonRepository $pokemonRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?PokemonReadDto
    {
        $pokemon = $this->pokemonRepository->find($uriVariables['id']);

        if (!$pokemon) {
            throw new NotFoundHttpException();
        }

        $dto = new PokemonReadDto();
        $dto->id = $pokemon->getId();
        $dto->nameFr = $pokemon->getNameFr();
        $dto->nameEn = $pokemon->getNameEn();
        $dto->dexNumber = $pokemon->getSpecies()->getPokedexNumber();
        $sprite = $pokemon->getSprite();

        if ($sprite) {
            $dto->sprites = [
                'default' => $sprite->getFrontDefault(),
                'female' => $sprite->getFrontFemale(),
                'shiny' => $sprite->getFrontShiny(),
                'shiny_female' => $sprite->getFrontShinyFemale(),
            ];
        }

        foreach ($pokemon->getTypes() as $pokemonType) {
            $dto->types[] = $pokemonType->getType()->getName();
        }

        foreach ($pokemon->getAbilities() as $pa) {
            $dto->abilities[] = [
                'name' => $pa->getAbility()->getNameFr(),
                'description' => $pa->getAbility()->getDescription(),
                'hidden' => $pa->isHidden()
            ];
        }

        $dto->stats = [
            'hp' => $pokemon->getHp(),
            'attack' => $pokemon->getAttack(),
            'defense' => $pokemon->getDefense(),
            'specialAttack' => $pokemon->getSpecialAttack(),
            'specialDefense' => $pokemon->getSpecialDefense(),
            'speed' => $pokemon->getSpeed(),
        ];

        return $dto;
    }
}
