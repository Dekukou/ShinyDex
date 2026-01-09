<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Pokemon;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:pokemon-stats',
    description: 'Import Pokémon base stats (per form) from PokeAPI'
)]
class ImportPokemonStatsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pokemonRepo = $this->em->getRepository(Pokemon::class);
        $pokemons = $pokemonRepo->findAll();

        foreach ($pokemons as $pokemon) {
            if (!$pokemon->getNameEn()) {
                continue;
            }

            $apiData = $this->client->get('pokemon/' . $pokemon->getNameEn());

            foreach ($apiData['stats'] as $statData) {
                $this->mapStat($pokemon, $statData);
            }

            $this->em->persist($pokemon);
            $name = $pokemon->getNameFr();
            $output->writeln("✔ Stats: $name");
        }

        $this->em->flush();

        $output->writeln('<info>✔ Pokémon stats imported</info>');
        return Command::SUCCESS;
    }

    private function mapStat(Pokemon $pokemon, array $statData): void
    {
        $value = $statData['base_stat'];

        match ($statData['stat']['name']) {
            'hp' => $pokemon->setHp($value),
            'attack' => $pokemon->setAttack($value),
            'defense' => $pokemon->setDefense($value),
            'special-attack' => $pokemon->setSpecialAttack($value),
            'special-defense' => $pokemon->setSpecialDefense($value),
            'speed' => $pokemon->setSpeed($value),
            default => null,
        };
    }
}
