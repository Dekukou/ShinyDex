<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\PokemonSpecies;
use App\Entity\Pokedex\Generation;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'pokeapi:import:pokemon-species',
    description: 'Import Pokémon species from PokéAPI'
)]
class ImportPokemonSpeciesCommand extends Command
{
    public function __construct(
        private PokeApiClient $api,
        private PokeApiTranslationHelper $translator,
        private EntityManagerInterface $em
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
        $list = $this->api->get('pokemon-species?limit=2000');

        foreach ($list['results'] as $result) {
            $data = $this->api->getByUrl($result['url']);
            $tmp = $result['url'];
            $output->writeln("✔ Species: $tmp");

            $species = $this->em->getRepository(PokemonSpecies::class)
                ->findOneBy(['pokedexNumber' => $data['id']]) ?? new PokemonSpecies();

            $generationName =
                $data['generation']['name'];

            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $generationName]);

            // $output->writeln("✔ Species: $generation");

            $name = $this->translator->getName($data['names'], 'fr');

            $species
                ->setPokedexNumber($data['id'])
                ->setNameEn($this->translator->getPokemonName($data['names'], 'en'))
                ->setNameFr($this->translator->getPokemonName($data['names'], 'fr'))
                ->setCategory(
                    $this->translator->getFrenchGenus($data['genera'])
                )
                ->setDescriptionFr(
                    $this->translator->getFlavorText($data['flavor_text_entries'], 'fr')
                )
                ->setIsLegendary($data['is_legendary'])
                ->setIsMythical($data['is_mythical'])
                ->setHasGenderDifference($data['has_gender_differences'])
                ->setCaptureRate($data['capture_rate'])
                ->setBaseHappiness($data['base_happiness'])
                ->setGeneration($generation);

            $this->em->persist($species);

            $output->writeln("✔ Species: $name");
        }

        if (!$dryRun) {
            $this->em->flush();
        }
        return Command::SUCCESS;
    }
}
