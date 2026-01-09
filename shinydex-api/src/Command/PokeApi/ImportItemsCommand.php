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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Importing evolution items...</info>');

        $items = $this->api->get('/item?limit=2000');

        foreach ($items['results'] as $entry) {
            $data = $this->api->getByUrl($entry['url']);

            // ✅ FILTER: only evolution items
            if (($data['category']['name'] ?? null) !== 'evolution') {
                continue;
            }

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

            $this->em->persist($item);

            $output->writeln(
                sprintf('✔ Evolution item: %s', $item->getNameFr() ?? $item->getApiName())
            );
        }

        $this->em->flush();

        $output->writeln('<info>✔ Evolution items imported successfully</info>');

        return Command::SUCCESS;
    }
}
