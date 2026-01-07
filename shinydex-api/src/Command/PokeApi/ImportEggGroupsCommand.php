<?php

namespace App\Command\PokeApi;

use App\Entity\Reproduction\EggGroup;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'pokeapi:import:egg-groups')]
class ImportEggGroupsCommand extends Command
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
        $list = $this->api->get('egg-group?limit=50');

        foreach ($list['results'] as $result) {
            $data = $this->api->getByUrl($result['url']);

            $name = $this->translator->getName($data['names']);

            $group = $this->em->getRepository(EggGroup::class)
                ->findOneBy(['name' => $name]) ?? new EggGroup();

            $group->setName($name);
            $this->em->persist($group);
            $output->writeln("✔ EggGroup: $name");
        }

        if (!$dryRun) {
            $this->em->flush();
        }
        return Command::SUCCESS;
    }
}
