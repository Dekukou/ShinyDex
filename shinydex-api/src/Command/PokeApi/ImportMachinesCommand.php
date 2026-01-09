<?php

namespace App\Command\PokeApi;

use App\Entity\Fight\Machine;
use App\Entity\Fight\Attack;
use App\Entity\Pokedex\VersionGroup;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'pokeapi:import:machines',
    description: 'Import machines (CT / CS / DT) from PokéAPI'
)]
class ImportMachinesCommand extends Command
{
    private array $versionGroupCache = [];

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $api
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $list = $this->api->get('/machine?limit=2000');

        foreach ($list['results'] as $entry) {
            $data = $this->api->getByUrl($entry['url']);

            /** ---------- Attack ---------- */
            $attack = $this->em->getRepository(Attack::class)
                ->findOneBy(['apiName' => $data['move']['name']]);

            if (!$attack) {
                continue;
            }

            /** ---------- VersionGroup ---------- */
            $vgData = $this->api->getByUrl($data['version_group']['url']);
            $vgName = $vgData['name'];

            if (!isset($this->versionGroupCache[$vgName])) {
                $this->versionGroupCache[$vgName] = $this->em
                    ->getRepository(VersionGroup::class)
                    ->findOneBy(['apiName' => $vgName]);
            }

            $versionGroup = $this->versionGroupCache[$vgName];

            if (!$versionGroup) {
                continue;
            }

            /** ---------- Machine ---------- */
            $machineName = $this->normalizeMachineName($data['item']['name']);

            $machine = $this->em->getRepository(Machine::class)
                ->findOneBy([
                    'name' => $machineName,
                    'versionGroup' => $versionGroup,
                ]) ?? new Machine();

            $machine
                ->setName($machineName)
                ->setVersionGroup($versionGroup)
                ->setAttack($attack);

            $this->em->persist($machine);
            $output->writeln("✔ Machine: $machineName ({$versionGroup->getApiName()})");
        }

        $this->em->flush();

        $output->writeln('<info>✔ Machines imported</info>');
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
