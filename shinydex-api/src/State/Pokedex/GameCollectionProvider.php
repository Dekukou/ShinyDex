<?php

namespace App\State\Pokedex;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Pokedex\GameReadDto;
use App\Repository\Pokedex\GameRepository;

class GameCollectionProvider implements ProviderInterface
{
    public function __construct(
        private GameRepository $gameRepository
    ) {}

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): iterable {
        // JOIN FETCH pour éviter N+1 et références circulaires
        $games = $this->gameRepository->createQueryBuilder('g')
            ->addSelect('gen', 'vg')
            ->join('g.generation', 'gen')
            ->join('g.versionGroup', 'vg')
            ->orderBy('gen.id', 'ASC')
            ->addOrderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult();

        $items = [];

        foreach ($games as $game) {
            $dto = new GameReadDto();
            $dto->id = $game->getId();
            $dto->name = $game->getName();
            $dto->generation = $game->getGeneration()->getName();
            $dto->versionGroup = $game->getVersionGroup()->getName();

            $items[] = $dto;
        }

        return $items;
    }
}
