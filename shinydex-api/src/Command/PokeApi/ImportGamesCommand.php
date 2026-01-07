<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Game;
use App\Entity\Pokedex\Generation;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:import:games',
    description: 'Import Pokémon games (versions) from PokeAPI'
)]
class ImportGamesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit items')
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Offset')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run (no flush)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = (int) $input->getOption('limit') ?: null;
        $offset = (int) $input->getOption('offset') ?: 0;
        $dryRun = $input->getOption('dry-run');
        $versions = $this->client->get('version?limit=200');

        foreach ($versions['results'] as $versionData) {
            $version = $this->client->getByUrl($versionData['url']);

            $versionGroup = $this->client->getByUrl(
                $version['version_group']['url']
            );

            $generationData = $this->client->getByUrl(
                $versionGroup['generation']['url']
            );

            $generationName = $generationData['name'];

            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $generationName]);

            if (!$generation) {
                continue;
            }

            $game = $this->em->getRepository(Game::class)
                ->findOneBy(['name' => $version['name']]);

            if (!$game) {
                $game = new Game();
                $name = $version['name'];
                $game
                    ->setName($version['name'])
                    ->setGeneration($generation);

                $this->em->persist($game);
                $output->writeln("✔ Games: $name");
            }
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        $output->writeln('<info>✔ Games imported</info>');
        return Command::SUCCESS;
    }
}
