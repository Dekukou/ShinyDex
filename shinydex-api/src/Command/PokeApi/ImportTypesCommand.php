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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $list = $this->api->get('type?limit=50');

        foreach ($list['results'] as $result) {
            $data = $this->api->getByUrl($result['url']);

            $name = $this->translator->getName($data['names']);

            $type = $this->em->getRepository(Type::class)
                ->findOneBy(['name' => $name]) ?? new Type();

            $type->setApiName($result['name'])
                ->setName($name);

            $this->em->persist($type);
            $output->writeln("✔ Type: $name");
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
