<?php

namespace App\State\User;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\User\UserCreateDto;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): User {
        /** @var UserCreateDto $data */

        $user = new User();
        $user->setUsername($data->username);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $data->password)
        );
        $user->setRoles(['ROLE_USER']);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
