<?php

namespace App\State\PokemonMove;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\Pagination\TraversablePaginator;
use App\Dto\PokemonMoveReadDto;
use App\Entity\Move\PokemonMove;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PokemonMoveCollectionProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $pokemonId = $uriVariables['id'] ?? null;

        if (!$pokemonId) {
            throw new NotFoundHttpException('Pokemon id is required.');
        }

        $filters = $context['filters'] ?? [];

        // Pagination
        $page = $filters['page'] ?? 1;
        $limit = $filters['itemsPerPage'] ?? 20;

        $paginator = $this->getPaginator($pokemonId, $filters, $page, $limit);

        // Mapping vers DTO
        $items = [];

        foreach ($paginator as $pokemonMove) {

            $move = $pokemonMove->getMove();

            $dto = new PokemonMoveReadDto();
            $dto->id = $pokemonMove->getId();
            $dto->name = $move->getName();
            $dto->type = $move->getType()->getName();

            $dto->power = $move->getPower();
            $dto->accuracy = $move->getAccuracy();
            $dto->pp = $move->getPp();
            $dto->damageClass = $move->getDamageClass();
            $dto->description = $move->getDescription();

            $dto->learnMethod = $pokemonMove->getLearnMethod();
            $dto->level = $pokemonMove->getLevel();
            $dto->versionGroup = $pokemonMove->getVersionGroup()->getName();

            $items[] = $dto;
        }

        return new TraversablePaginator(
            new \ArrayIterator($items),
            $page,
            $limit,
            $paginator->count()
        );
    }

    /**
     * Construit le paginator avec filtres et joins nécessaires
     */
    private function getPaginator(
        int $pokemonId,
        array $filters,
        int $page,
        int $limit
    ): Paginator {
        $offset = ($page - 1) * $limit;

        $qb = $this->entityManager->createQueryBuilder()
            ->select('pm', 'm', 't', 'vg')
            ->from(PokemonMove::class, 'pm')
            ->join('pm.move', 'm')
            ->join('m.type', 't')
            ->join('pm.versionGroup', 'vg')
            ->andWhere('pm.pokemon = :pokemon')
            ->setParameter('pokemon', $pokemonId)
            ->orderBy('pm.level', 'ASC');

        // 🎮 Filtre par versionGroup
        if (!empty($filters['versionGroup'])) {
            $qb
                ->andWhere('vg.apiName = :versionGroup')
                ->setParameter('versionGroup', $filters['versionGroup']);
        }

        // 📘 Filtre par méthode d’apprentissage
        if (!empty($filters['learnMethod'])) {
            $qb
                ->andWhere('pm.learnMethod = :learnMethod')
                ->setParameter('learnMethod', $filters['learnMethod']);
        }

        // 🔁 Pagination
        $qb
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return new Paginator($qb, true);
    }
}
