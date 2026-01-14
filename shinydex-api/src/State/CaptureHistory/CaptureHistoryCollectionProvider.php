<?php

namespace App\State\CaptureHistory;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\CaptureHistory\CaptureHistoryReadDto;
use App\Entity\Capture\PokemonCaptureHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class CaptureHistoryCollectionProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $user = $this->security->getUser();
        if (!$user) {
            return [];
        }

        $filters = $context['filters'] ?? [];

        $qb = $this->em->createQueryBuilder()
            ->select('c', 'p', 'g', 'b', 'hm')
            ->from(PokemonCaptureHistory::class, 'c')
            ->join('c.pokemon', 'p')
            ->join('c.game', 'g')
            ->join('c.ball', 'b')
            ->join('c.huntMethod', 'hm')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.capturedAt', 'DESC');

        // 🔍 Filtres
        if (isset($filters['isShiny'])) {
            $qb->andWhere('c.isShiny = :isShiny')
                ->setParameter('isShiny', filter_var($filters['isShiny'], FILTER_VALIDATE_BOOLEAN));
        }

        if (!empty($filters['pokemonId'])) {
            $qb->andWhere('p.id = :pokemonId')
                ->setParameter('pokemonId', $filters['pokemonId']);
        }

        if (!empty($filters['gameId'])) {
            $qb->andWhere('g.id = :gameId')
                ->setParameter('gameId', $filters['gameId']);
        }

        if (!empty($filters['fromDate'])) {
            $qb->andWhere('c.capturedAt >= :fromDate')
                ->setParameter('fromDate', new \DateTimeImmutable($filters['fromDate']));
        }

        if (!empty($filters['toDate'])) {
            $qb->andWhere('c.capturedAt <= :toDate')
                ->setParameter('toDate', new \DateTimeImmutable($filters['toDate']));
        }

        // 📄 Pagination
        $page = max(1, (int) ($filters['page'] ?? 1));
        $itemsPerPage = min(
            100,
            max(1, (int) ($filters['itemsPerPage'] ?? 30))
        );

        $qb
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $captures = $qb->getQuery()->getResult();

        return array_map(
            fn(PokemonCaptureHistory $capture) => $this->mapToDto($capture),
            $captures
        );
    }

    private function mapToDto(PokemonCaptureHistory $capture): CaptureHistoryReadDto
    {
        $dto = new CaptureHistoryReadDto();

        $dto->id = $capture->getId();
        $dto->pokemonId = $capture->getPokemon()->getId();
        $dto->pokemonName = $capture->getPokemon()->getNameFr();
        $dto->pokemonSprite = $capture->getPokemon()->getSprite()->getFrontShiny();

        $dto->isShiny = $capture->isShiny();
        $dto->gender = $capture->getGender();
        $dto->formKey = $capture->getFormKey();
        $dto->level = $capture->getLevel();
        $dto->isAlpha = $capture->isAlpha();

        $dto->game = $capture->getGame()->getName();
        $dto->ball = $capture->getBall()->getName();
        $dto->huntMethod = $capture->getHuntMethod()->getName();

        $dto->capturedAt = $capture->getCapturedAt();
        $dto->notes = $capture->getNotes();

        return $dto;
    }
}
