<?php

namespace App\State\Hunt;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\Hunt\HuntCreateDto;
use App\Dto\Hunt\HuntReadDto;
use App\Entity\Capture\HuntSession;
use App\Repository\Pokedex\PokemonRepository;
use App\Repository\Capture\HuntMethodRepository;
use App\Repository\Pokedex\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class HuntCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private PokemonRepository $pokemonRepository,
        private HuntMethodRepository $methodRepository,
        private GameRepository $gameRepository,
        private Security $security
    ) {}


    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): HuntReadDto
    {
        /** @var HuntCreateDto $data */

        $user = $this->security->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'User not authenticated');
        }

        $pokemon = $this->pokemonRepository->find($data->pokemonId);
        $method  = $this->methodRepository->find($data->methodId);
        $game    = $this->gameRepository->find($data->gameId);

        if (!$pokemon || !$method || !$game) {
            throw new \RuntimeException('Invalid hunt data');
        }

        $hunt = new HuntSession();
        $hunt->setUser($user);
        $hunt->setPokemon($pokemon);
        $hunt->setMethod($method);
        $hunt->setGame($game);
        $hunt->setCounter(0);
        $hunt->setIsShinyFound(false);
        $hunt->setStartedAt(new \DateTimeImmutable());

        $this->em->persist($hunt);
        $this->em->flush();

        $dto = new HuntReadDto();
        $dto->id = $hunt->getId();
        $dto->pokemonId = $pokemon->getId();
        $dto->pokemonName = $pokemon->getNameFr();
        $dto->sprite = $pokemon->getSprite()?->getFrontDefault();
        $dto->method = $method->getName();
        $dto->counter = 0;
        $dto->isShinyFound = false;
        $dto->startedAt = $hunt->getStartedAt();
        $dto->endedAt = null;

        return $dto;
    }
}
