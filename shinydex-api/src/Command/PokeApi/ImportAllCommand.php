<?php

namespace App\Command\PokeApi;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import:all',
    description: 'Launch all PokeAPI import commands'
)]
class ImportAllCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit number of items')
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Offset')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run (no database write)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = $input->getOption('limit');
        $offset = $input->getOption('offset');
        $dryRun = $input->getOption('dry-run');

        $io = new SymfonyStyle($input, $output);
        $application = $this->getApplication();

        $io->title('🚀 Global Pokédex import');

        $commands = [
            // 'pokeapi:import:types',
            // 'pokeapi:import:egg-groups',
            // 'pokeapi:import:abilities',
            // 'app:import:items', // TODO trop d'objets, je veux que ceux dévolution
            // 'pokeapi:import:attacks',
            // 'app:import:generations',
            // 'pokeapi:import:pokemon-species',
            // 'pokeapi:import:pokemons',
            // 'pokeapi:import:machines', TODO améliorer pour rajouter un gameVersion
            // 'app:import:evolutions',
            'app:import:pokemon-sprites',
        ];

        foreach ($commands as $commandName) {
            $io->section("▶ $commandName");

            $command = $application->find($commandName);

            $arguments = new ArrayInput([
                '--limit' => $limit,
                '--offset' => $offset,
                '--dry-run' => $dryRun,
            ]);

            $command->run($arguments, $output);
        }

        $io->success('All imports completed');

        return Command::SUCCESS;
    }
}
