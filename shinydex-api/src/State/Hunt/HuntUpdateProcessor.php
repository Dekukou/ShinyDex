<?php

namespace App\State\Hunt;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Hunt\HuntUpdateDto;
use App\Dto\Hunt\HuntReadDto;
use App\Repository\Capture\HuntSessionRepository;
use Doctrine\ORM\EntityManagerInterface;

class HuntUpdateProcessor implements ProcessorInterface
{
    public function __construct(
        private HuntSessionRepository $huntRepository,
        private EntityManagerInterface $em
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): HuntReadDto
    {
        /** @var HuntUpdateDto $data */
        $hunt = $this->huntRepository->find($uriVariables['id']);

        if ($data->counter !== null) {
            $hunt->setCounter($data->counter);
        }

        if ($data->isShinyFound === true) {
            $hunt->setIsShinyFound(true);
            $hunt->setEndedAt(new \DateTimeImmutable());
        }

        $this->em->flush();

        $pokemon = $hunt->getPokemon();

        $dto = new HuntReadDto();
        $dto->id = $hunt->getId();
        $dto->pokemonId = $pokemon->getId();
        $dto->pokemonName = $pokemon->getNameFr();
        $dto->sprite = $pokemon->getSprite()?->getFrontDefault();
        $dto->method = $hunt->getMethod()->getName();
        $dto->counter = $hunt->getCounter();
        $dto->isShinyFound = $hunt->isShinyFound();
        $dto->startedAt = $hunt->getStartedAt();
        $dto->endedAt = $hunt->getEndedAt();

        return $dto;
    }
}
