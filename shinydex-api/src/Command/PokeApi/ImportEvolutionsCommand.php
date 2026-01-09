<?php

namespace App\Command\PokeApi;

use App\Entity\Evolution\EvolutionTrigger;
use App\Entity\Evolution\PokemonEvolution;
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
    description: 'Import Pokémon evolutions with Shinydex rules'
)]
class ImportEvolutionsCommand extends Command
{
    private array $processedChains = [];
    private array $triggerCache = [];

    private const LOCATION_BASED_REGIONAL_EVOLUTIONS = [
        'Pikachu' => 'Raichu',
        'Cubone'  => 'Marowak',
    ];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $api
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $speciesList = $this->api->get('pokemon-species?limit=2000')['results'];

        foreach ($speciesList as $entry) {
            $speciesData = $this->api->getByUrl($entry['url']);

            if (!isset($speciesData['evolution_chain']['url'])) {
                continue;
            }

            $chainData = $this->api->getByUrl($speciesData['evolution_chain']['url']);
            $chainId = $chainData['id'];

            if (isset($this->processedChains[$chainId])) {
                continue;
            }

            $this->processedChains[$chainId] = true;
            $this->parseChain($chainData['chain'], null);

            $this->em->flush();
            $this->em->clear();
            $this->triggerCache = [];
        }

        // 🔥 Injection CANONIQUE des évolutions régionales par localisation
        $this->injectRegionalLocationEvolutions();

        $this->em->flush();

        $output->writeln('✔ Evolutions imported successfully');

        return Command::SUCCESS;
    }

    // ==================================================
    // CHAIN PARSING (inchangé)
    // ==================================================

    private function parseChain(array $node, ?Pokemon $fromPokemon): void
    {
        $speciesRepo = $this->em->getRepository(PokemonSpecies::class);
        $pokemonRepo = $this->em->getRepository(Pokemon::class);

        $species = $speciesRepo->findOneBy(['nameEn' => $node['species']['name']]);
        if (!$species) {
            return;
        }

        $currentPokemons = array_filter(
            $pokemonRepo->findBy(['species' => $species]),
            fn(Pokemon $p) => $this->isValidSourceForm($p)
        );

        if ($fromPokemon && !empty($node['evolution_details'])) {
            foreach ($currentPokemons as $toPokemon) {
                $this->createEvolution($fromPokemon, $toPokemon, $node['evolution_details']);
            }
        }

        foreach ($currentPokemons as $currentPokemon) {
            foreach ($node['evolves_to'] as $child) {
                $this->parseChain($child, $currentPokemon);
            }
        }
    }

    // ==================================================
    // MANUAL INJECTION (THE KEY)
    // ==================================================

    private function injectRegionalLocationEvolutions(): void
    {
        $pokemonRepo   = $this->em->getRepository(Pokemon::class);
        $speciesRepo   = $this->em->getRepository(PokemonSpecies::class);
        $evolutionRepo = $this->em->getRepository(PokemonEvolution::class);

        foreach (self::LOCATION_BASED_REGIONAL_EVOLUTIONS as $fromName => $toName) {

            $fromSpecies = $speciesRepo->findOneBy(['nameEn' => $fromName]);
            $toSpecies   = $speciesRepo->findOneBy(['nameEn' => $toName]);

            if (!$fromSpecies || !$toSpecies) {
                continue;
            }

            $fromPokemon = $pokemonRepo->findOneBy([
                'species' => $fromSpecies,
                'isDefault' => true,
            ]);

            if (!$fromPokemon) {
                continue;
            }

            foreach ($pokemonRepo->findBy(['species' => $toSpecies]) as $regionalForm) {
                if (!$regionalForm->getRegionForm()) {
                    continue;
                }

                if ($evolutionRepo->findOneBy([
                    'fromPokemon' => $fromPokemon,
                    'toPokemon' => $regionalForm,
                ])) {
                    continue;
                }
                $this->em->persist(
                    (new PokemonEvolution())
                        ->setFromPokemon($fromPokemon)
                        ->setToPokemon($regionalForm)
                        ->setTrigger($this->getOrCreateTrigger('level-up'))
                );
            }
        }
    }

    // ==================================================
    // HELPERS
    // ==================================================

    private function createEvolution(Pokemon $from, Pokemon $to, array $details): void
    {
        $repo = $this->em->getRepository(PokemonEvolution::class);
        if (!$this->isValidTargetForm($from, $to)) {
            return;
        }
        if ($repo->findOneBy(['fromPokemon' => $from, 'toPokemon' => $to])) {
            return;
        }

        $detail = $details[0];

        $conditions = $this->buildConditions($to, $detail);

        $this->em->persist(
            (new PokemonEvolution())
                ->setFromPokemon($from)
                ->setToPokemon($to)
                ->setTrigger($this->getOrCreateTrigger($detail['trigger']['name']))
                ->setMinLevel($detail['min_level'] ?? null)
                ->setExtraCondition(
                    empty($conditions) ? null : json_encode($conditions, JSON_UNESCAPED_UNICODE)
                )
        );
    }

    private function isValidSourceForm(Pokemon $pokemon): bool
    {
        $key = $pokemon->getFormKey();

        return !$pokemon->isMega()
            && !$pokemon->isGmax()
            && !str_contains($key, '-totem')
            && !str_starts_with($key, 'pikachu-');
    }

    private function isValidTargetForm(Pokemon $from, Pokemon $to): bool
    {

        // Cas spécial : Miaouss (Meowth)
        if ($from->getSpecies()->getNameEn() === 'Meowth') {

            $fromRegion = $from->getRegionForm();
            $toName     = strtolower($to->getSpecies()->getNameEn());

            if ($fromRegion) {
                // Galar → Berserkatt uniquement
                if ($fromRegion->getName() === 'Galar') {
                    return $toName === 'perrserker';
                }

                // Alola → Persian d'Alola uniquement
                if ($fromRegion->getName() === 'Alola') {
                    return $toName === 'persian' && $to->getRegionForm();
                }
            }
            // Kanto → Persian uniquement
            return $toName === 'persian' && !$to->getRegionForm();
        }

        // Si la cible est régionale
        if ($to->getRegionForm()) {

            // Autorisé si la source est déjà régionale
            if ($from->getRegionForm()) {
                return true;
            }

            // Autorisé UNIQUEMENT pour les exceptions par localisation
            return array_key_exists(
                $from->getSpecies()->getNameEn(),
                self::LOCATION_BASED_REGIONAL_EVOLUTIONS
            );
        }

        // Cas normal : régional → normal interdit
        if ($from->getRegionForm() && !$to->getRegionForm()) {
            return false;
        }

        return true;
    }

    private function getOrCreateTrigger(string $name): EvolutionTrigger
    {
        if (!isset($this->triggerCache[$name])) {
            $repo = $this->em->getRepository(EvolutionTrigger::class);
            $this->triggerCache[$name] = $repo->findOneBy(['name' => $name])
                ?? (new EvolutionTrigger())->setName($name);
            $this->em->persist($this->triggerCache[$name]);
        }
        return $this->triggerCache[$name];
    }

    private function buildConditions(
        Pokemon $to,
        array $detail
    ): array {
        $conditions = [];

        if (!empty($detail['gender'])) {
            $conditions['gender'] = $detail['gender'];
        }

        if (!empty($detail['held_item'])) {
            $conditions['held_item'] = $detail['held_item'];
        }

        if (!empty($detail['known_move'])) {
            $conditions['known_move'] = $detail['known_move']['name'];
        }

        if (!empty($detail['known_move_type'])) {
            $conditions['known_move_type'] = $detail['known_move_type']['name'];
        }

        if (!empty($detail['location'])) {
            $conditions['location'] = $detail['location']['name'];
        }

        if (!empty($detail['min_affection'])) {
            $conditions['min_affection'] = $detail['min_affection'];
        }

        if (!empty($detail['min_beauty'])) {
            $conditions['min_beauty'] = $detail['min_beauty'];
        }

        if (!empty($detail['min_happiness'])) {
            $conditions['min_happiness'] = $detail['min_happiness'];
        }

        if (!empty($detail['needs_overworld_rain'])) {
            $conditions['needs_overworld_rain'] = $detail['needs_overworld_rain'];
        }

        if (!empty($detail['party_species'])) {
            $conditions['party_species'] = $detail['party_species'];
        }

        if (!empty($detail['party_type'])) {
            $conditions['party_type'] = $detail['party_type']['name'];
        }

        if (!empty($detail['region_id'])) {
            $conditions['region_id'] = $detail['region_id'];
        }

        if (!empty($detail['relative_physical_stats'])) {
            $conditions['relative_physical_stats'] = $detail['relative_physical_stats'];
        }

        if (!empty($detail['time_of_day'])) {
            $conditions['time_of_day'] = $detail['time_of_day'];
        }

        if (!empty($detail['trade_species'])) {
            $conditions['trade_species'] = $detail['trade_species']['name'];
        }

        if (!empty($detail['turn_upside_down'])) {
            $conditions['turn_upside_down'] = $detail['turn_upside_down'];
        }

        if ($to->getRegionForm()) {
            $conditions['region'] = $to->getRegionForm()->getName();
        }

        return $conditions;
    }
}
