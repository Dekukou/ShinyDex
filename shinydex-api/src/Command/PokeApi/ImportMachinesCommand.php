<?php

namespace App\Command\PokeApi;

use App\Entity\Fight\Machine;
use App\Entity\Fight\Attack;
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
    name: 'pokeapi:import:machines',
    description: 'Import machines (TM / HM / TR) from PokéAPI'
)]
class ImportMachinesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $api,
        private readonly PokeApiTranslationHelper $translator,
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
        $output->writeln('<info>Importing machines...</info>');

        $list = $this->api->get('/machine?limit=2000');

        foreach ($list['results'] as $entry) {
            $data = $this->api->getByUrl($entry['url']);
            /** ---------- Attack ---------- */
            $attack = $this->em->getRepository(Attack::class)
                ->findOneBy(['apiName' => $data['move']['name']]);

            if (!$attack) {
                $output->writeln(
                    sprintf('<comment>Attack not found: %s</comment>', $data['move']['name'])
                );
                continue;
            }

            $versionGroup = $this->api->getByUrl(
                $data['version_group']['url']
            );

            $generationData = $this->api->getByUrl(
                $versionGroup['generation']['url']
            );

            $generationName = $generationData['name'];

            /** ---------- Generation ---------- */
            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $generationName]);

            if (!$generation) {
                $output->writeln(
                    sprintf('<comment>Generation not found: %s</comment>', $generationName)
                );
                continue;
            }

            /** ---------- Machine name ---------- */
            // Exemple : "tm01", "tr45"
            $rawName = strtoupper($data['item']['name']); // TM01, HM03, TR45

            $machineName = $this->normalizeMachineName($data['item']['name']);

            /** ---------- Create / Update ---------- */
            $machine = $this->em->getRepository(Machine::class)
                ->findOneBy([
                    'name' => $machineName,
                    'generation' => $generation,
                ]) ?? new Machine();

            $machine
                ->setName($machineName)
                ->setGeneration($generation)
                ->setAttack($attack);
            $this->em->persist($machine);
            $output->writeln("✔ Machine: $machineName");
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        $output->writeln('<info>Machines imported successfully</info>');

        return Command::SUCCESS;
    }

    private function normalizeMachineName(string $apiName): string
    {
        $name = strtoupper($apiName);

        return match (true) {
            str_starts_with($name, 'TM') => 'CT' . substr($name, 2),
            str_starts_with($name, 'HM') => 'CS' . substr($name, 2),
            default => 'DT' . substr($name, 2),
        };
    }
}
