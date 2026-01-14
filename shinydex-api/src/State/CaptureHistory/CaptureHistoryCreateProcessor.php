<?php

namespace App\State\CaptureHistory;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\CaptureHistory\CaptureHistoryCreateDto;
use App\Entity\Capture\PokemonCaptureHistory;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\Game;
use App\Entity\Capture\Ball;
use App\Entity\Capture\HuntMethod;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CaptureHistoryCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): PokemonCaptureHistory
    {
        if (!$data instanceof CaptureHistoryCreateDto) {
            throw new \LogicException('Invalid DTO');
        }

        $user = $this->security->getUser();

        if (!$user) {
            throw new \LogicException('User must be authenticated');
        }

        $pokemon = $this->em->getRepository(Pokemon::class)->find($data->pokemonId);
        $game = $this->em->getRepository(Game::class)->find($data->gameId);
        $ball = $this->em->getRepository(Ball::class)->find($data->ballId);
        $huntMethod = $this->em->getRepository(HuntMethod::class)->find($data->huntMethodId);

        if (!$pokemon || !$game || !$ball || !$huntMethod) {
            throw new NotFoundHttpException('Invalid reference provided');
        }

        $capture = new PokemonCaptureHistory();
        $capture
            ->setUser($user)
            ->setPokemon($pokemon)
            ->setGame($game)
            ->setBall($ball)
            ->setHuntMethod($huntMethod)
            ->setIsShiny($data->isShiny)
            ->setGender($data->gender)
            ->setFormKey($data->formKey)
            ->setLevel($data->level)
            ->setIsAlpha($data->isAlpha)
            ->setNotes($data->notes);

        $this->em->persist($capture);
        $this->em->flush();

        return $capture;
    }
}
