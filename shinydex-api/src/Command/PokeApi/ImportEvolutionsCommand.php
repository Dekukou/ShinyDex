<?php

namespace App\Command\PokeApi;

use App\Entity\Evolution\EvolutionTrigger;
use App\Entity\Evolution\PokemonEvolution;
use App\Entity\Evolution\PokemonFamily;
use App\Entity\Evolution\Item;
use App\Entity\Pokedex\Pokemon;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:import:evolutions',
    description: 'Import Pokémon evolutions and families from PokeAPI'
)]
class ImportEvolutionsCommand extends Command
{
    private array $triggerCache = [];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('limit', null, InputOption::VALUE_OPTIONAL, 'Limit species')
            ->addOption('offset', null, InputOption::VALUE_OPTIONAL, 'Offset')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run (no flush)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit  = $input->getOption('limit');
        $offset = (int) ($input->getOption('offset') ?? 0);
        $dryRun = $input->getOption('dry-run');

        $output->writeln('<info>Starting evolutions import...</info>');

        $query = 'pokemon-species?limit=2000';
        $speciesList = $this->client->get($query)['results'];

        if ($limit !== null) {
            $speciesList = array_slice($speciesList, $offset, (int) $limit);
        }

        foreach ($speciesList as $speciesData) {
            $species = $this->client->getByUrl($speciesData['url']);

            if (!isset($species['evolution_chain']['url'])) {
                continue;
            }

            $chainData = $this->client->getByUrl($species['evolution_chain']['url']);

            $family = $this->getOrCreateFamily($chainData);
            $pokemonName = $family->getName();
            $output->writeln("✔ Evolution: $pokemonName");
            $this->parseChain(
                $chainData['chain'],
                null,
                $family
            );
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        $output->writeln('<info>✔ Evolutions imported successfully</info>');

        return Command::SUCCESS;
    }

    // ==================================================
    // FAMILY
    // ==================================================

    private function getOrCreateFamily(array $chainData): PokemonFamily
    {
        $rootName = $chainData['chain']['species']['name'];

        $family = $this->em->getRepository(PokemonFamily::class)
            ->findOneBy(['name' => $rootName]);

        if (!$family) {
            $family = (new PokemonFamily())->setName($rootName);
            $this->em->persist($family);
        }

        return $family;
    }

    // ==================================================
    // RECURSIVE CHAIN PARSING
    // ==================================================

    private function parseChain(
        array $node,
        ?Pokemon $from,
        PokemonFamily $family
    ): void {
        $pokemonRepo = $this->em->getRepository(Pokemon::class);
        $itemRepo    = $this->em->getRepository(Item::class);
        $evoRepo     = $this->em->getRepository(PokemonEvolution::class);

        $to = $pokemonRepo->findOneBy([
            'nameEn' => $node['species']['name']
        ]);

        if (!$to) {
            return;
        }

        // 🔗 Link Pokémon to its family
        $to->setFamily($family);

        if ($from) {
            foreach ($node['evolution_details'] as $detail) {
                $trigger = $this->getOrCreateTrigger($detail['trigger']['name']);

                // ❌ Prevent duplicate evolutions
                $existing = $evoRepo->findOneBy([
                    'fromPokemon' => $from,
                    'toPokemon'   => $to,
                    'trigger'     => $trigger,
                ]);

                if ($existing) {
                    continue;
                }

                $evolution = (new PokemonEvolution())
                    ->setFromPokemon($from)
                    ->setToPokemon($to)
                    ->setTrigger($trigger)
                    ->setMinLevel($detail['min_level'] ?? null)
                    ->setTradeRequired($detail['trigger']['name'] === 'trade')
                    ->setExtraCondition(
                        $this->extractExtraConditions($detail)
                    );

                if (!empty($detail['item'])) {
                    $item = $itemRepo->findOneBy([
                        'apiName' => $detail['item']['name']
                    ]);
                    if ($item) {
                        $evolution->setRequiredItem($item);
                    }
                }

                $this->em->persist($evolution);
            }
        }

        foreach ($node['evolves_to'] as $child) {
            $this->parseChain($child, $to, $family);
        }
    }

    // ==================================================
    // TRIGGERS
    // ==================================================

    private function getOrCreateTrigger(string $name): EvolutionTrigger
    {
        if (isset($this->triggerCache[$name])) {
            return $this->triggerCache[$name];
        }

        $repo = $this->em->getRepository(EvolutionTrigger::class);
        $trigger = $repo->findOneBy(['name' => $name]);

        if (!$trigger) {
            $trigger = (new EvolutionTrigger())->setName($name);
            $this->em->persist($trigger);
        }

        $this->triggerCache[$name] = $trigger;

        return $trigger;
    }


    // ==================================================
    // EXTRA CONDITIONS
    // ==================================================

    private function extractExtraConditions(array $detail): ?string
    {
        $ignoredKeys = ['trigger', 'item', 'min_level'];
        $extras = [];

        foreach ($detail as $key => $value) {
            if (in_array($key, $ignoredKeys, true) || $value === null) {
                continue;
            }

            if (is_array($value) && isset($value['name'])) {
                $extras[$key] = $value['name'];
            } else {
                $extras[$key] = $value;
            }
        }

        return empty($extras)
            ? null
            : json_encode($extras, JSON_UNESCAPED_UNICODE);
    }
}
