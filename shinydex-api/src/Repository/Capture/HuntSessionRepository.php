<?php

namespace App\Repository\Capture;

use App\Entity\Capture\HuntSession;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HuntSession>
 */
class HuntSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HuntSession::class);
    }

    public function findByUser(
        User $user,
        ?bool $onlyOngoing = false
    ): array {
        $qb = $this->createQueryBuilder('h')
            ->andWhere('h.user = :user')
            ->setParameter('user', $user)
            ->orderBy('h.startedAt', 'DESC');

        if ($onlyOngoing) {
            $qb->andWhere('h.endedAt IS NULL');
        }

        return $qb->getQuery()->getResult();
    }
}
