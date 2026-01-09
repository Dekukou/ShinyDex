<?php

namespace App\Command\PokeApi;

use App\Entity\Ability\Ability;
use App\Repository\Ability\AbilityRepository;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'pokeapi:import:abilities',
    description: 'Import abilities from PokeAPI (handles As One variants)'
)]
class ImportAbilitiesCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AbilityRepository $abilityRepository,
        private readonly PokeApiClient $pokeApiClient,
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
        $output->writeln('<info>Importing abilities...</info>');

        $list = $this->pokeApiClient->get('ability?limit=1000');

        foreach ($list['results'] as $entry) {
            $abilityData = $this->pokeApiClient->getByUrl($entry['url']);

            $apiName = $abilityData['name'];

            // 🔑 clé unique technique
            $ability = $this->abilityRepository->findOneBy([
                'apiName' => $apiName,
            ]);

            if (!$ability) {
                $ability = new Ability();
                $ability->setApiName($apiName);
                $this->em->persist($ability);
            }

            // 🇫🇷 Nom FR via TON helper
            $nameFr = $this->translator->getFrenchName(
                $apiName,
                'ability'
            );

            // 🇬🇧 Nom EN (fallback simple)
            $nameEn = ucfirst(str_replace('-', ' ', $apiName));

            $description = null;
            foreach ($abilityData['flavor_text_entries'] ?? [] as $effect) {
                if (($effect['language']['name'] ?? null) === 'fr') {
                    $description = $effect['flavor_text'] ?? null;
                    break;
                }
            }

            $ability
                ->setNameFr($nameFr ?? $nameEn)
                ->setNameEn($nameEn)
                ->setDescription($description);

            $output->writeln(sprintf(
                ' - %s (%s)',
                $ability->getNameFr(),
                $apiName
            ));
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        $output->writeln('<info>Abilities import completed.</info>');

        return Command::SUCCESS;
    }
}
