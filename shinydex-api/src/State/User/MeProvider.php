<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\User\MeReadDto;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class MeProvider implements ProviderInterface
{
    public function __construct(
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): MeReadDto
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new UnauthorizedHttpException('Bearer', 'User not authenticated');
        }

        $dto = new MeReadDto();
        $dto->id = $user->getId();
        $dto->username = $user->getUserIdentifier();
        $dto->roles = $user->getRoles();

        return $dto;
    }
}
