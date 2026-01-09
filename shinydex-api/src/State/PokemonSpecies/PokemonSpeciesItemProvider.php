<?php

namespace App\State\PokemonSpecies;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\PokemonFormDto;
use App\Dto\PokemonSpeciesReadDto;
use App\Entity\Pokedex\Pokemon;
use App\Repository\Pokedex\PokemonSpeciesRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PokemonSpeciesItemProvider implements ProviderInterface
{
    public function __construct(
        private PokemonSpeciesRepository $pokemonSpeciesRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?PokemonSpeciesReadDto
    {
        $qb = $this->pokemonSpeciesRepository->createQueryBuilder('ps')
            ->leftJoin('ps.generation', 'g')
            ->leftJoin('ps.pokemons', 'p')
            ->leftJoin('p.types', 'pt')
            ->leftJoin('pt.type', 't')
            ->leftJoin('p.sprite', 's')
            ->addSelect('g', 'p', 'pt', 't', 's')
            ->andWhere('ps.id = :id')
            ->setParameter('id', $uriVariables['id']);

        $pokemonSpecie = $qb->getQuery()->getOneOrNullResult();

        if (!$pokemonSpecie) {
            throw new NotFoundHttpException();
        }

        // Pokémon par défaut
        /** @var Pokemon|null $defaultPokemon */
        $defaultPokemon = $pokemonSpecie->getPokemons()
            ->filter(fn(Pokemon $p) => $p->getIsDefault())
            ->first() ?: null;

        if (!$defaultPokemon) {
            throw new NotFoundHttpException('No default Pokémon found.');
        }

        // DTO espèce
        $dto = new PokemonSpeciesReadDto();
        $dto->id = $pokemonSpecie->getId();
        $dto->dexNumber = $pokemonSpecie->getPokedexNumber();
        $dto->nameFr = $pokemonSpecie->getNameFr();
        $dto->nameEn = $pokemonSpecie->getNameEn();
        $dto->generation = $pokemonSpecie->getGeneration()->getName();

        // Types par défaut
        $dto->types = array_map(
            fn($pt) => $pt->getType()->getName(),
            $defaultPokemon->getTypes()->toArray()
        );

        // Formes
        foreach ($pokemonSpecie->getPokemons() as $pokemon) {

            $form = new PokemonFormDto();
            $form->id = $pokemon->getId();
            $form->formKey = $pokemon->getFormKey();
            $form->isDefault = $pokemon->getIsDefault();
            $form->name = $pokemon->getNameFr();

            $form->types = array_map(
                fn($pt) => $pt->getType()->getName(),
                $pokemon->getTypes()->toArray()
            );

            $form->sprites = [
                'default' => $pokemon->getSprite()?->getFrontDefault(),
                'shiny' => $pokemon->getSprite()?->getFrontShiny(),
            ];

            $dto->forms[] = $form;
        }

        return $dto;
    }
}
