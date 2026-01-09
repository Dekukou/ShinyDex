<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Generation;
use App\Service\PokeApi\PokeApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import:generations',
    description: 'Import Pokémon generations from PokeAPI'
)]
class ImportGenerationsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly PokeApiClient $client
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $data = $this->client->get('generation?limit=20');

        foreach ($data['results'] as $genData) {
            $genApi = $this->client->getByUrl($genData['url']);

            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $genApi['name']]);

            if (!$generation) {
                $generation = new Generation();
                $name = $genApi['name'];
                $generation->setName($genApi['name']);
                $this->em->persist($generation);
                $output->writeln("✔ Type: $name");
            }
        }

        $this->em->flush();

        $output->writeln('<info>✔ Generations imported</info>');
        return Command::SUCCESS;
    }
}
