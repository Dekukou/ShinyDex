<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Generation;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:import:generations',
    description: 'Import Pokémon generations from PokeAPI'
)]
class ImportGenerationsCommand extends Command
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
        $data = $this->client->get('generation?limit=20');

        foreach ($data['results'] as $genData) {
            $genApi = $this->client->getByUrl($genData['url']);

            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $genApi['name']]);

            if (!$generation) {
                $generation = new Generation();
                $name = $genApi['name'];
                $generation->setName($genApi['name']);
                $this->em->persist($generation);
                $output->writeln("✔ Type: $name");
            }
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        $output->writeln('<info>✔ Generations imported</info>');
        return Command::SUCCESS;
    }
}
