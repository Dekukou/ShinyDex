<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use App\Dto\PokemonReadDto;
use App\Repository\Pokedex\PokemonRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PokemonCollectionProvider implements ProviderInterface
{
    public function __construct(
        private PokemonRepository $pokemonRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $filters = $context['filters'] ?? [];

        // Pagination
        $page = $context['filters']['page'] ?? 1;
        $limit = $context['filters']['itemsPerPage'] ?? 30;

        $doctrinePaginator = $this->getPaginator($filters, $page, $limit);

        // Mapping vers DTO
        $items = [];
        foreach ($doctrinePaginator as $pokemon) {
            $dto = new PokemonReadDto();
            $dto->id = $pokemon->getId();
            $dto->nameFr = $pokemon->getNameFr();
            $dto->nameEn = $pokemon->getNameEn();
            $dto->dexNumber = $pokemon->getSpecies()->getPokedexNumber();
            $sprite = $pokemon->getSprite();

            $dto->sprites = [
                'default' => $sprite?->getFrontDefault(),
                'shiny' => $sprite?->getFrontShiny(),
            ];

            foreach ($pokemon->getTypes() as $pokemonType) {
                $dto->types[] = $pokemonType->getType()->getName();
            }

            $items[] = $dto;
        }

        return new TraversablePaginator(
            new \ArrayIterator($items),
            $page,
            $limit,
            $doctrinePaginator->count()
        );
    }

    private function getPaginator(array $filters, int $page, int $limit)
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->pokemonRepository->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC');

        // 🔤 Filtre par nom (FR / EN)
        if (!empty($filters['name'])) {
            $qb
                ->andWhere('p.nameFr LIKE :name OR p.nameEn LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        // 🧬 Filtre par type
        if (!empty($filters['type'])) {
            $qb
                ->join('p.types', 'pt')
                ->join('pt.type', 't')
                ->andWhere('t.name = :type')
                ->setParameter('type', $filters['type']);
        }

        // 🧭 Filtre par génération
        if (!empty($filters['generation'])) {
            $qb
                ->join('p.species', 's')
                ->join('s.generation', 'g')
                ->andWhere('g.id = :generation')
                ->setParameter('generation', $filters['generation']);
        }

        if (!empty($filters['species'])) {
            $qb
                ->andWhere('p.species = :species')
                ->setParameter('species', $filters['species']);
        }

        // 🔁 Pagination
        $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($qb);
    }
}
