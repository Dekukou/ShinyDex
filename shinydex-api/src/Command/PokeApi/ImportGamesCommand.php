<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Game;
use App\Entity\Pokedex\Generation;
use App\Entity\Pokedex\VersionGroup;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:games',
    description: 'Import Pokémon games (versions) from PokeAPI'
)]
class ImportGamesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client,
        private PokeApiTranslationHelper $translator,
    ) {
        parent::__construct();
    }

    private array $versionGroupCache = [];

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $versions = $this->client->get('version?limit=200');

        foreach ($versions['results'] as $versionData) {
            $version = $this->client->getByUrl($versionData['url']);

            $versionGroupData = $this->client->getByUrl(
                $version['version_group']['url']
            );

            $versionGroup = $this->getOrCreateVersionGroup($versionGroupData);

            $generationName = $versionGroup->getGeneration()->getName();

            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $generationName]);

            if (!$generation) {
                continue;
            }

            $game = $this->em->getRepository(Game::class)
                ->findOneBy(['name' => $version['name']]);

            if (!$game) {
                $game = new Game();
                $name = $version['name'];
                $game
                    ->setName($version['name'])
                    ->setVersionGroup($versionGroup)
                    ->setGeneration($generation);

                $this->em->persist($game);
                $output->writeln("✔ Games: $name");
            }
        }

        $this->em->flush();

        $output->writeln('<info>✔ Games imported</info>');
        return Command::SUCCESS;
    }

    private function getOrCreateVersionGroup(array $versionGroupData): VersionGroup
    {
        $apiName = $versionGroupData['name'];

        // ✅ 1. Cache mémoire (le plus important)
        if (isset($this->versionGroupCache[$apiName])) {
            return $this->versionGroupCache[$apiName];
        }

        // ✅ 2. DB lookup (si cache vide)
        $repo = $this->em->getRepository(VersionGroup::class);
        $versionGroup = $repo->findOneBy(['apiName' => $apiName]);

        if (!$versionGroup) {
            $generationData = $this->client->getByUrl(
                $versionGroupData['generation']['url']
            );

            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $generationData['name']]);

            $versionGroup = (new VersionGroup())
                ->setApiName($apiName)
                ->setName(
                    $this->formatVersionGroupName($versionGroupData['name'])
                )
                ->setGeneration($generation);

            $this->em->persist($versionGroup);
        }

        // ✅ 3. Mise en cache
        $this->versionGroupCache[$apiName] = $versionGroup;

        return $versionGroup;
    }

    private function formatVersionGroupName(string $apiName): string
    {
        return ucwords(str_replace('-', ' / ', $apiName));
    }
}
