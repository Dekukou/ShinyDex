<?php

namespace App\Command\PokeApi;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:import:all',
    description: 'Launch all PokeAPI import commands'
)]
class ImportAllCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commands = [
            'pokeapi:import:types',
            'pokeapi:import:egg-groups',
            'pokeapi:import:abilities',
            'app:import:items',
            'pokeapi:import:attacks',
            'app:import:generations',
            'app:import:games',
            'pokeapi:import:pokemon-species',
            'pokeapi:import:machines',
            'pokeapi:import:pokemons',
            // ? Uncomment this line only if you want to retrieve changes to the Pokemon moves.
            // 'app:import:pokemon-moves',
            'app:import:evolutions',
            'app:import:pokemon-sprites',
        ];

        foreach ($commands as $command) {
            $output->writeln("\n▶ Running <info>$command</info>");

            $process = new Process(['php', 'bin/console', $command]);
            $process->setTimeout(null);
            $process->run(function ($type, $buffer) use ($output) {
                $output->write($buffer);
            });

            if (!$process->isSuccessful()) {
                $output->writeln("<error>❌ Command failed: $command</error>");
                return Command::FAILURE;
            }
        }

        $output->writeln("\n✅ <info>All imports completed successfully</info>");

        return Command::SUCCESS;
    }
}
