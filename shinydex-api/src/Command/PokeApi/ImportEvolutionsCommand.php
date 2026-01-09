<?php

namespace App\Command\PokeApi;

use App\Entity\Evolution\EvolutionTrigger;
use App\Entity\Evolution\PokemonEvolution;
use App\Entity\Evolution\PokemonFamily;
use App\Entity\Evolution\Item;
use App\Entity\Pokedex\Pokemon;
use App\Entity\Pokedex\PokemonSpecies;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:evolutions',
    description: 'Import Pokémon evolutions and families from PokeAPI'
)]
class ImportEvolutionsCommand extends Command
{
    private array $processedChains = [];
    private array $triggerCache = [];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $speciesList = $this->client->get('pokemon-species?limit=2000')['results'];

        foreach ($speciesList as $speciesData) {
            $species = $this->client->getByUrl($speciesData['url']);

            if (!isset($species['evolution_chain']['url'])) {
                continue;
            }

            $chainData = $this->client->getByUrl($species['evolution_chain']['url']);
            $chainId = $chainData['id'];

            // ✅ Skip already processed chains
            if (isset($this->processedChains[$chainId])) {
                continue;
            }

            $this->processedChains[$chainId] = true;

            $family = $this->getOrCreateFamily($chainData);

            // // ✅ Attach ALL varieties (forms) to family
            // $this->attachVarietiesToFamily($species, $family);

            $this->attachAllFormsToFamily($chainData, $family);


            // ✅ Parse evolution chain ONCE
            $this->parseChain($chainData['chain'], null, $family);

            $this->em->flush();

            $output->writeln("ChainID $chainId");

            $this->em->clear();
            $this->triggerCache = [];
        }

        $output->writeln('<info>✔ Evolutions imported successfully</info>');
        return Command::SUCCESS;
    }

    // ==================================================
    // FAMILY
    // ==================================================

    private function getOrCreateFamily(array $chainData): PokemonFamily
    {
        $chainId = $chainData['id'];

        $family = $this->em
            ->getRepository(PokemonFamily::class)
            ->findOneBy(['chainId' => $chainId]);

        if (!$family) {
            $family = (new PokemonFamily())
                ->setChainId($chainId)
                ->setName($chainData['chain']['species']['name']);

            $this->em->persist($family);
        }

        return $family;
    }

    // ==================================================
    // VARIETIES (FORMS)
    // ==================================================

    private function attachVarietiesToFamily(array $species, PokemonFamily $family): void
    {
        $pokemonRepo = $this->em->getRepository(Pokemon::class);

        foreach ($species['varieties'] as $variety) {
            $pokemon = $pokemonRepo->findOneBy([
                'formKey' => $variety['pokemon']['name']
            ]);

            if ($pokemon) {
                $pokemon->setFamily($family);
            }
        }
    }

    private function collectSpeciesFromChain(array $node, array &$speciesNames): void
    {
        $speciesNames[] = $node['species']['name'];

        foreach ($node['evolves_to'] as $child) {
            $this->collectSpeciesFromChain($child, $speciesNames);
        }
    }

    private function attachAllFormsToFamily(array $chainData, PokemonFamily $family): void
    {
        $speciesNames = [];
        $this->collectSpeciesFromChain($chainData['chain'], $speciesNames);

        $pokemonRepo = $this->em->getRepository(Pokemon::class);

        foreach ($speciesNames as $speciesName) {
            $speciesData = $this->client->get('/pokemon-species/' . $speciesName);

            foreach ($speciesData['varieties'] as $variety) {
                $pokemon = $pokemonRepo->findOneBy([
                    'formKey' => $variety['pokemon']['name']
                ]);

                if ($pokemon) {
                    $pokemon->setFamily($family);
                }
            }
        }
    }

    // ==================================================
    // EVOLUTION CHAIN
    // ==================================================

    private function parseChain(
        array $node,
        ?Pokemon $from,
        PokemonFamily $family
    ): void {
        $pokemonRepo = $this->em->getRepository(Pokemon::class);
        $itemRepo = $this->em->getRepository(Item::class);

        $speciesRepo = $this->em->getRepository(PokemonSpecies::class);

        $species = $speciesRepo->findOneBy([
            'nameEn' => $node['species']['name']
        ]);

        if (!$species) {
            return;
        }

        $to = $pokemonRepo->findOneBy([
            'species' => $species,
            'regionForm' => null // forme par défaut
        ]);

        if (!$to) {
            return;
        }

        $to->setFamily($family);

        if ($from) {
            // dd($from);
            foreach ($node['evolution_details'] as $detail) {

                // ✅ Avoid duplicate evolutions
                $existing = $this->em->getRepository(PokemonEvolution::class)
                    ->findOneBy([
                        'fromPokemon' => $from,
                        'toPokemon' => $to,
                        'minLevel' => $detail['min_level']
                    ]);

                if ($existing) {
                    continue;
                }

                $evolution = new PokemonEvolution();
                $evolution
                    ->setFromPokemon($from)
                    ->setToPokemon($to)
                    ->setTrigger(
                        $this->getOrCreateTrigger($detail['trigger']['name'])
                    )
                    ->setMinLevel($detail['min_level'] ?? 0)
                    ->setTradeRequired($detail['trigger']['name'] === 'trade')
                    ->setExtraCondition(
                        $this->extractExtraConditions($detail)
                    );

                if ($detail['item']) {
                    $item = $itemRepo->findOneBy([
                        'apiName' => $detail['item']['name']
                    ]);
                    $evolution->setRequiredItem($item);
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

        $trigger = $this->em
            ->getRepository(EvolutionTrigger::class)
            ->findOneBy(['name' => $name]);

        if (!$trigger) {
            $trigger = (new EvolutionTrigger())->setName($name);
            $this->em->persist($trigger);
        }

        return $this->triggerCache[$name] = $trigger;
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
