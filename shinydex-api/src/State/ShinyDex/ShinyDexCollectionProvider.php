<?php

namespace App\State\ShinyDex;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Dto\ShinyDex\ShinyDexReadDto;
use App\Dto\ShinyDex\ShinyDexBoxDto;
use App\Dto\ShinyDex\ShinyDexEntryDto;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Capture\PokemonCaptureHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class ShinyDexCollectionProvider implements ProviderInterface
{
    private const BOX_SIZE = 30;

    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ShinyDexReadDto
    {
        $user = $this->security->getUser();

        $dto = new ShinyDexReadDto();

        /** ----------------------------
         *  1️⃣ Shinys capturés par l'user
         *  ---------------------------- */
        $captured = $this->em->createQueryBuilder()
            ->select('IDENTITY(c.pokemon) AS pokemonId, c.formKey')
            ->from(PokemonCaptureHistory::class, 'c')
            ->where('c.user = :user')
            ->andWhere('c.isShiny = true')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $capturedMap = [];
        foreach ($captured as $row) {
            $key = $row['pokemonId'] . '|' . ($row['formKey'] ?? 'base');
            $capturedMap[$key] = true;
        }

        /** ----------------------------
         *  2️⃣ Pokédex national
         *  ---------------------------- */
        $nationalPokemon = $this->em->getRepository(Pokemon::class)
            ->createQueryBuilder('p')
            ->where('p.isDefault = true')
            ->join('p.species', 'ps')
            ->orderBy('ps.pokedexNumber', 'ASC')
            ->getQuery()
            ->getResult();

        $dto->totalBasePokemon = count($nationalPokemon);

        $boxes = [];
        $boxIndex = 1;

        foreach (array_chunk($nationalPokemon, self::BOX_SIZE) as $chunk) {
            $box = $this->buildBox(
                $chunk,
                $capturedMap,
                $boxIndex,
                'national',
                'Box ' . $boxIndex
            );

            $dto->totalBaseShiny += $box->shinyCount;
            $dto->totalGlobalShiny += $box->shinyCount;

            $boxes[] = $box;
            $boxIndex++;
        }

        /** ----------------------------
         *  3️⃣ Formes spéciales
         *  ---------------------------- */
        $formGroups = [
            'alola' => 'Alola Forms',
            'galar' => 'Galar Forms',
            'hisui' => 'Hisui Forms',
            'gender' => 'Gender Differences',
        ];

        foreach ($formGroups as $formType => $label) {
            $forms = $this->em->getRepository(Pokemon::class)

                ->createQueryBuilder('p')
                ->join('p.species', 'ps')
                ->where('p.regionForm = :type')
                ->setParameter('type', $formType)
                ->orderBy('ps.pokedexNumber', 'ASC')
                ->getQuery()
                ->getResult();

            foreach (array_chunk($forms, self::BOX_SIZE) as $chunk) {
                $box = $this->buildBox(
                    $chunk,
                    $capturedMap,
                    $boxIndex,
                    $formType,
                    $label
                );

                $dto->totalGlobalShiny += $box->shinyCount;

                $boxes[] = $box;
                $boxIndex++;
            }
        }

        $dto->boxes = $boxes;

        return $dto;
    }

    /** --------------------------------
     *  Construction d'une box
     *  -------------------------------- */
    private function buildBox(
        array $pokemonList,
        array $capturedMap,
        int $index,
        string $type,
        string $label
    ): ShinyDexBoxDto {
        $box = new ShinyDexBoxDto();
        $box->boxIndex = $index;
        $box->boxType = $type;
        $box->label = $label;
        $box->total = count($pokemonList);

        foreach ($pokemonList as $pokemon) {
            $entry = new ShinyDexEntryDto();
            $entry->pokedexNumber = $pokemon->getSpecies()->getPokedexNumber();
            $entry->name = $pokemon->getNameFr();
            $entry->sprite = $pokemon->getSprite()->getFrontShiny();
            // $entry->formType = $pokemon->getFormType();
            $entry->formType = "test";
            $entry->formKey = $pokemon->getFormKey();

            $captureKey = $pokemon->getId() . '|' . ($entry->formKey ?? 'base');
            $entry->isShinyCaptured = isset($capturedMap[$captureKey]);

            if ($entry->isShinyCaptured) {
                $box->shinyCount++;
            }

            $box->entries[] = $entry;
        }

        $box->completion = $box->total > 0
            ? round(($box->shinyCount / $box->total) * 100, 2)
            : 0;

        return $box;
    }
}
