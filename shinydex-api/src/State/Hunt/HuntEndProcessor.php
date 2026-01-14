<?php

namespace App\State\Hunt;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Capture\HuntSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class HuntEndProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): HuntSession {
        /** @var HuntSession $hunt */

        $hunt = $context['previous_data'];

        if ($hunt->getUser() !== $this->security->getUser()) {
            throw new AccessDeniedHttpException();
        }

        if ($hunt->getEndedAt() === null) {
            $hunt->setEndedAt(new \DateTimeImmutable());
            $this->em->flush();
        }

        return $hunt;
    }
}
