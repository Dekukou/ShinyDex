<?php

namespace App\State\Hunt;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\Hunt\HuntReadDto;
use App\Repository\Capture\HuntSessionRepository;
use Symfony\Bundle\SecurityBundle\Security;

class HuntCollectionProvider implements ProviderInterface
{
    public function __construct(
        private HuntSessionRepository $huntRepository,
        private Security $security
    ) {}

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): iterable {
        $user = $this->security->getUser();

        if (!$user) {
            return [];
        }

        $filters = $context['filters'] ?? [];
        $onlyOngoing = ($filters['status'] ?? null) === 'ongoing';

        $hunts = $this->huntRepository->findByUser(
            $user,
            $onlyOngoing
        );

        $items = [];

        foreach ($hunts as $hunt) {
            $pokemon = $hunt->getPokemon();

            $dto = new HuntReadDto();
            $dto->id = $hunt->getId();
            $dto->pokemonId = $pokemon->getId();
            $dto->pokemonName = $pokemon->getNameFr();
            $dto->sprite = $pokemon->getSprite()?->getFrontShiny();
            $dto->method = $hunt->getMethod()->getName();
            $dto->counter = $hunt->getCounter();
            $dto->startedAt = $hunt->getStartedAt();
            $dto->endedAt = $hunt->getEndedAt();

            $items[] = $dto;
        }

        return $items;
    }
}
