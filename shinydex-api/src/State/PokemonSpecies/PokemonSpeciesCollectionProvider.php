<?php

namespace App\State\PokemonSpecies;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use App\Dto\PokemonSpeciesReadDto;
use App\Entity\Pokedex\Pokemon;
use App\Repository\Pokedex\PokemonSpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PokemonSpeciesCollectionProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private PokemonSpeciesRepository $pokemonSpeciesRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $filters = $context['filters'] ?? [];

        // Pagination
        $page = $filters['page'] ?? 1;
        $limit = $filters['itemsPerPage'] ?? 30;

        $paginator = $this->getPaginator($filters, $page, $limit);

        $items = [];

        foreach ($paginator as $species) {

            $defaultPokemon = $species->getPokemons()->first();

            if (!$defaultPokemon) {
                continue; // sécurité, normalement jamais atteint
            }

            $dto = new PokemonSpeciesReadDto();
            $dto->id = $species->getId();
            $dto->dexNumber = $species->getPokedexNumber();
            $dto->nameFr = $species->getNameFr();
            $dto->nameEn = $species->getNameEn();
            $dto->generation = $species->getGeneration()->getName();

            $dto->types = array_map(
                fn($pt) => $pt->getType()->getName(),
                $defaultPokemon->getTypes()->toArray()
            );

            $dto->sprite = $defaultPokemon->getSprite()?->getFrontDefault();

            $items[] = $dto;
        }

        return new TraversablePaginator(
            new \ArrayIterator($items),
            $page,
            $limit,
            $paginator->count()
        );
    }


    private function getPaginator(array $filters, int $page, int $limit): Paginator
    {
        $offset = ($page - 1) * $limit;

        $qb = $this->pokemonSpeciesRepository
            ->createQueryBuilder('ps')
            // Pokémon par défaut (1 seul par espèce)
            ->join('ps.pokemons', 'p')
            ->andWhere('p.isDefault = true')
            // Types
            ->join('p.types', 'pt')
            ->join('pt.type', 't')
            // Sprite (nullable)
            ->leftJoin('p.sprite', 's')
            // FETCH
            ->addSelect('p', 'pt', 't', 's')
            ->orderBy('ps.id', 'ASC');

        // 🔤 Filtre par nom (FR / EN)
        if (!empty($filters['name'])) {
            $qb
                ->andWhere('ps.nameFr LIKE :name OR ps.nameEn LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        // 🧬 Filtre par type (mono / double)
        if (!empty($filters['type'])) {

            $types = array_values(array_filter(array_map(
                'trim',
                explode(',', $filters['type'])
            )));

            // 🟢 MONO-TYPE
            if (count($types) === 1) {
                $subQb = $this->em->createQueryBuilder()
                    ->select('IDENTITY(p2.species)')
                    ->from(Pokemon::class, 'p2')
                    ->join('p2.types', 'pt2')
                    ->join('pt2.type', 't2')
                    ->where('p2.isDefault = true')
                    ->andWhere('t2.name = :type');

                $qb
                    ->andWhere($qb->expr()->in('ps.id', $subQb->getDQL()))
                    ->setParameter('type', $types[0]);
            }

            // 🔵 DOUBLE-TYPE EXACT (sous-requête)
            if (count($types) === 2) {

                $subQb = $this->em->createQueryBuilder()
                    ->select('IDENTITY(p2.species)')
                    ->from(Pokemon::class, 'p2')
                    ->join('p2.types', 'pt2')
                    ->join('pt2.type', 't2')
                    ->where('p2.isDefault = true')
                    ->andWhere('t2.name IN (:types)')
                    ->groupBy('p2.id')
                    ->having('COUNT(DISTINCT t2.id) = :typeCount');

                $qb
                    ->andWhere($qb->expr()->in('ps.id', $subQb->getDQL()))
                    ->setParameter('types', $types)
                    ->setParameter('typeCount', 2);
            }
        }

        // 🧭 Filtre par génération
        if (!empty($filters['generation'])) {
            $qb
                ->andWhere('ps.generation = :generation')
                ->setParameter('generation', $filters['generation']);
        }

        // 🔁 Pagination
        $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($qb, true);
    }
}
