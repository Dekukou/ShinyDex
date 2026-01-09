<?php

namespace App\State\PokemonEvolution;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\PokemonEvolutionStepDto;
use App\Entity\Evolution\PokemonEvolution;
use App\Entity\Pokedex\Pokemon;
use App\Repository\Pokedex\PokemonSpeciesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PokemonEvolutionChainProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PokemonSpeciesRepository $pokemonSpeciesRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $speciesId = $uriVariables['id'] ?? null;

        if (!$speciesId) {
            throw new NotFoundHttpException('Pokemon species id is required.');
        }

        // 🔎 Récupération de l'espèce + Pokémon par défaut
        $species = $this->pokemonSpeciesRepository->find($speciesId);

        if (!$species) {
            throw new NotFoundHttpException();
        }

        /** @var Pokemon|null $defaultPokemon */
        $defaultPokemon = $species->getPokemons()
            ->filter(fn(Pokemon $p) => $p->getIsDefault())
            ->first() ?: null;

        if (!$defaultPokemon) {
            throw new NotFoundHttpException('No default Pokémon found.');
        }

        // 🧬 Chargement de TOUTES les évolutions de la lignée
        $evolutions = $this->getEvolutionsWithJoins();

        // 🗺 Indexation par fromPokemonId
        $map = [];
        foreach ($evolutions as $evolution) {
            $fromId = $evolution->getFromPokemon()->getId();
            $map[$fromId][] = $evolution;
        }

        // 🔍 Trouver la racine de la chaîne
        $rootId = $this->findRootPokemonId($defaultPokemon->getId(), $evolutions);

        // 🔁 Construction récursive de la chaîne
        $result = [];
        $this->buildChain($rootId, $map, $result);

        return $result;
    }

    /**
     * Charge toutes les évolutions avec JOIN FETCH
     */
    private function getEvolutionsWithJoins(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select(
                'e',
                'fp',
                'fps',
                'fpt',
                'ft',
                'tp',
                'tps',
                'tpt',
                'tt',
                'tr',
                'i'
            )
            ->from(PokemonEvolution::class, 'e')

            // From Pokémon
            ->join('e.fromPokemon', 'fp')
            ->leftJoin('fp.sprite', 'fps')
            ->leftJoin('fp.types', 'fpt')
            ->leftJoin('fpt.type', 'ft')

            // To Pokémon
            ->join('e.toPokemon', 'tp')
            ->leftJoin('tp.sprite', 'tps')
            ->leftJoin('tp.types', 'tpt')
            ->leftJoin('tpt.type', 'tt')

            // Evolution info
            ->join('e.trigger', 'tr')
            ->leftJoin('e.requiredItem', 'i')

            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve le Pokémon racine de la chaîne
     */
    private function findRootPokemonId(int $startPokemonId, array $evolutions): int
    {
        $toIds = [];

        foreach ($evolutions as $evolution) {
            $toIds[] = $evolution->getToPokemon()->getId();
        }

        // Si le Pokémon de départ n'est jamais une cible, c'est la racine
        if (!in_array($startPokemonId, $toIds, true)) {
            return $startPokemonId;
        }

        // Sinon on remonte
        foreach ($evolutions as $evolution) {
            if ($evolution->getToPokemon()->getId() === $startPokemonId) {
                return $this->findRootPokemonId(
                    $evolution->getFromPokemon()->getId(),
                    $evolutions
                );
            }
        }

        return $startPokemonId;
    }

    /**
     * Construit récursivement la chaîne d'évolution
     */
    private function buildChain(
        int $fromPokemonId,
        array $map,
        array &$result
    ): void {
        if (!isset($map[$fromPokemonId])) {
            return;
        }

        foreach ($map[$fromPokemonId] as $evolution) {

            $result[] = $this->mapEvolutionToDto($evolution);

            $this->buildChain(
                $evolution->getToPokemon()->getId(),
                $map,
                $result
            );
        }
    }

    /**
     * Mapping Evolution → DTO
     */
    private function mapEvolutionToDto(PokemonEvolution $evolution): PokemonEvolutionStepDto
    {
        $dto = new PokemonEvolutionStepDto();

        $dto->from = $this->mapPokemon($evolution->getFromPokemon());
        $dto->to = $this->mapPokemon($evolution->getToPokemon());

        $dto->trigger = $evolution->getTrigger()->getName();
        $dto->minLevel = $evolution->getMinLevel();
        $dto->tradeRequired = $evolution->getTradeRequired();
        $dto->item = $evolution->getRequiredItem()?->getNameFr();
        $dto->conditions = $evolution->getExtraCondition();

        return $dto;
    }

    /**
     * Mapping Pokémon → array (sprite + types)
     */
    private function mapPokemon(Pokemon $pokemon): array
    {
        return [
            'id' => $pokemon->getId(),
            'name' => $pokemon->getNameFr(),
            'sprite' => $pokemon->getSprite()?->getFrontDefault(),
            'types' => array_map(
                fn($pt) => $pt->getType()->getName(),
                $pokemon->getTypes()->toArray()
            ),
        ];
    }
}
