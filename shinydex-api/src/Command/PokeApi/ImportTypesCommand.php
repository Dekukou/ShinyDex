<?php

namespace App\Command\PokeApi;

use App\Entity\Fight\Type;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'pokeapi:import:types',
    description: 'Import Pokémon types from PokéAPI'
)]
class ImportTypesCommand extends Command
{
    public function __construct(
        private PokeApiClient $api,
        private PokeApiTranslationHelper $translator,
        private EntityManagerInterface $em
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
        $list = $this->api->get('type?limit=50');

        foreach ($list['results'] as $result) {
            $data = $this->api->getByUrl($result['url']);

            $name = $this->translator->getName($data['names']);

            $type = $this->em->getRepository(Type::class)
                ->findOneBy(['name' => $name]) ?? new Type();

            $type->setName($name);

            $this->em->persist($type);
            $output->writeln("✔ Type: $name");
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        return Command::SUCCESS;
    }
}
