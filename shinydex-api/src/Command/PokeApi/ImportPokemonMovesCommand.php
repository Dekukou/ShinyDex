<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Pokemon;
use App\Entity\Fight\Attack;
use App\Entity\Move\PokemonMove;
use App\Entity\Pokedex\VersionGroup;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:pokemon-moves',
    description: 'Import Pokémon moves (level-up, CT, CS, DT)'
)]
class ImportPokemonMovesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // 🔥 ANTI MEMORY LEAK
        ini_set('memory_limit', '-1');
        gc_enable();
        putenv('SYMFONY_HTTP_CLIENT_DEBUG=0');

        $pokemonRepo = $this->em->getRepository(Pokemon::class);
        $attackRepo = $this->em->getRepository(Attack::class);
        $versionGroupRepo = $this->em->getRepository(VersionGroup::class);

        $batchSize = 50;
        $i = 0;

        foreach ($pokemonRepo->findAll() as $pokemon) {

            $seenMoves = [];

            $formKey = $pokemon->getFormKey();

            if (
                str_contains($formKey, 'mega') ||
                str_contains($formKey, 'gmax') ||
                str_contains($formKey, 'pikachu-')
            ) {
                continue;
            }

            // ⚠️ Important : on s'assure que le Pokémon est MANAGED
            $pokemon = $this->em->getReference(Pokemon::class, $pokemon->getId());

            $apiData = $this->client->get('/pokemon/' . $formKey);

            // 🔥 ON NE GARDE QUE LES MOVES
            $moves = $apiData['moves'] ?? [];
            unset($apiData);

            foreach ($moves as $moveData) {
                $attack = $attackRepo->findOneBy([
                    'apiName' => $moveData['move']['name']
                ]);

                if (!$attack) {
                    continue;
                }

                // Attack MANAGED
                $attack = $this->em->getReference(Attack::class, $attack->getId());

                foreach ($moveData['version_group_details'] as $detail) {
                    $versionGroup = $versionGroupRepo->findOneBy([
                        'apiName' => $detail['version_group']['name']
                    ]);

                    if (!$versionGroup) {
                        continue;
                    }

                    // VersionGroup MANAGED
                    $versionGroup = $this->em->getReference(
                        VersionGroup::class,
                        $versionGroup->getId()
                    );

                    $learnMethod = $detail['move_learn_method']['name'];
                    $level = $detail['level_learned_at'] ?: null;

                    $key = implode('-', [
                        $pokemon->getId(),
                        $attack->getId(),
                        $versionGroup->getId(),
                        $learnMethod,
                        $level ?? 0
                    ]);

                    // 🚫 DUPLICAT API → on skip
                    if (isset($seenMoves[$key])) {
                        continue;
                    }

                    $seenMoves[$key] = true;

                    $pokemonMove = new PokemonMove();
                    $pokemonMove
                        ->setPokemon($pokemon)
                        ->setMove($attack)
                        ->setVersionGroup($versionGroup)
                        ->setLearnMethod($learnMethod)
                        ->setLevel($level);

                    $this->em->persist($pokemonMove);
                    $i++;
                }
            }

            // 🔁 FLUSH / CLEAR PAR BATCH
            if ($i >= $batchSize) {
                $this->em->flush();
                $this->em->clear();

                gc_collect_cycles();
                $i = 0;
            }

            $output->writeln('✔ ' . $pokemon->getNameFr());
        }

        // 🔚 FLUSH FINAL
        $this->em->flush();
        $this->em->clear();

        $output->writeln('<info>✔ Pokémon moves import terminé sans fuite mémoire</info>');

        return Command::SUCCESS;
    }
}
