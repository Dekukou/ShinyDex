<?php

namespace App\Command\PokeApi;

use App\Entity\Evolution\Item;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:import:items',
    description: 'Import evolution items from PokéAPI'
)]
class ImportItemsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private PokeApiClient $api,
        private PokeApiTranslationHelper $translator,
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
        $items = $this->api->get('/item?limit=2000');

        foreach ($items['results'] as $entry) {
            $data = $this->api->getByUrl($entry['url']);

            $item = $this->em->getRepository(Item::class)
                ->findOneBy(['apiName' => $data['name']]) ?? new Item();

            $item
                ->setApiName($data['name'])
                ->setNameEn(
                    $this->translator->getName($data['names'], 'en')
                )
                ->setNameFr(
                    $this->translator->getName($data['names'], 'fr')
                );

            $name = $this->translator->getName($data['names'], 'fr');

            $this->em->persist($item);
            $output->writeln("✔ Items: $name");
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        return Command::SUCCESS;
    }
}
