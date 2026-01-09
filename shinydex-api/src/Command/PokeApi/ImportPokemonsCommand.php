<?php

namespace App\Command\PokeApi;

use App\Entity\Ability\Ability;
use App\Entity\Ability\PokemonAbility;
use App\Entity\Fight\PokemonType;
use App\Entity\Fight\Type;
use App\Entity\Pokedex\Generation;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\PokemonSpecies;
use App\Entity\Pokedex\RegionForm;
use App\Entity\Reproduction\EggGroup;
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
        $generationRepo = $this->em->getRepository(Generation::class);

        $speciesList = $this->client->get('pokemon-species?limit=2000')['results'];

        foreach ($speciesList as $speciesData) {
            $speciesApi = $this->client->getByUrl($speciesData['url']);

            $species = $speciesRepo->findOneBy([
                'pokedexNumber' => $speciesApi['id']
            ]);

            $generation = $generationRepo->findOneBy([
                'name' => $speciesApi['generation']['name']
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
                    'formKey' => $pokemonApi['name']
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
                    ->setFormKey($pokemonApi['name'])
                    ->setNameFr(
                        $this->translator->getFrenchName(
                            $pokemonApi['name'],
                            'pokemon'
                        ) ?? $species->getNameFr()
                    )
                    ->setNameEn(
                        $this->translator->getFrenchName(
                            $pokemonApi['name'],
                            'pokemon'
                        ) ?? $species->getNameEn()
                    )
                    ->setRegionForm($regionForm)
                    ->setGeneration($generation)
                    ->setIsDefault($pokemonApi['is_default']);

                foreach ($pokemonApi['stats'] as $stat) {
                    $this->mapStat($pokemon, $stat);
                }


                $this->attachEggGroups($pokemon, $speciesApi);
                $this->attachTypes($pokemon, $pokemonApi);
                $this->attachAbilities($pokemon, $pokemonApi);


                $this->em->persist($pokemon);
                $name = $pokemonApi['name'];
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

    private function attachEggGroups(
        Pokemon $pokemon,
        array $speciesData
    ): void {
        $eggGroupRepo = $this->em->getRepository(EggGroup::class);

        foreach ($speciesData['egg_groups'] as $eggGroupData) {
            $eggGroup = $eggGroupRepo->findOneBy([
                'apiName' => $eggGroupData['name']
            ]);

            $pokemon->addEggGroup($eggGroup);
        }
    }

    private function attachTypes(Pokemon $pokemon, array $pokemonData): void
    {
        $typeRepo = $this->em->getRepository(Type::class);

        foreach ($pokemonData['types'] as $typeData) {
            $type = $typeRepo->findOneBy([
                'apiName' => $typeData['type']['name']
            ]);

            $pokemonType = (new PokemonType())
                ->setPokemon($pokemon)
                ->setType($type)
                ->setSlot($typeData['slot']);

            $this->em->persist($pokemonType);
        }
    }

    private function attachAbilities(
        Pokemon $pokemon,
        array $pokemonData
    ): void {
        $abilityRepo = $this->em->getRepository(Ability::class);
        $abilityCache = [];

        foreach ($pokemonData['abilities'] as $abilityData) {
            if (!isset($abilityData['ability']['name'])) {
                $ability = $abilityRepo->findOneBy([
                    'apiName' => $abilityData['ability']['name']
                ]);

                $pokemonAbility = new PokemonAbility();
                $pokemonAbility
                    ->setPokemon($pokemon)
                    ->setAbility($ability)
                    ->setIsHidden($abilityData['is_hidden'])
                    ->setSlot($abilityData['slot']);

                $abilityCache[$abilityData['ability']['name']];
                $this->em->persist($pokemonAbility);
            }
        }
    }
}
