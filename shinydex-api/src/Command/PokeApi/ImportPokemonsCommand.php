<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\PokemonSpecies;
use App\Entity\Pokedex\RegionForm;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'pokeapi:import:pokemons',
    description: 'Import Pokémon forms from PokeAPI'
)]
class ImportPokemonsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client,
        private readonly PokeApiTranslationHelper $translator
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
        $output->writeln('<info>Starting Pokémon import...</info>');

        $speciesRepo = $this->em->getRepository(PokemonSpecies::class);
        $regionFormRepo = $this->em->getRepository(RegionForm::class);
        $pokemonRepo = $this->em->getRepository(Pokemon::class);

        $speciesList = $this->client->get('pokemon-species?limit=2000')['results'];

        foreach ($speciesList as $speciesData) {
            $speciesApi = $this->client->getByUrl($speciesData['url']);

            $species = $speciesRepo->findOneBy([
                'pokedexNumber' => $speciesApi['id']
            ]);

            if (!$species) {
                continue;
            }

            foreach ($speciesApi['varieties'] as $variety) {
                if (!$variety['pokemon']) {
                    continue;
                }

                $pokemonApi = $this->client->getByUrl($variety['pokemon']['url']);

                // Évite les doublons
                $existingPokemon = $pokemonRepo->findOneBy([
                    'nameEn' => $pokemonApi['name']
                ]);

                if ($existingPokemon) {
                    continue;
                }

                $regionForm = null;
                foreach ($regionFormRepo->findAll() as $rf) {
                    if (str_contains($pokemonApi['name'], strtolower($rf->getName()))) {
                        $regionForm = $rf;
                        break;
                    }
                }

                $pokemon = new Pokemon();
                $pokemon
                    ->setSpecies($species)
                    ->setNameEn($pokemonApi['name'])
                    ->setNameFr(
                        $this->translator->getFrenchName(
                            $pokemonApi['name'],
                            'pokemon'
                        ) ?? $species->getNameFr()
                    )
                    ->setRegionForm($regionForm);

                $name =
                    $this->translator->getFrenchName(
                        $pokemonApi['name'],
                        'pokemon'
                    ) ?? $species->getNameFr();
                $this->em->persist($pokemon);
                $output->writeln("✔ Pokemon: $name");
            }

            if (!$dryRun) {
                $this->em->flush();
            }
            $this->em->clear();
        }

        $output->writeln('<info>✔ Pokémon imported successfully</info>');
        return Command::SUCCESS;
    }
}
