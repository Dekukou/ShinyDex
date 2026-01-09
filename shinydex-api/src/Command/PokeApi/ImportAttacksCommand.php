<?php

namespace App\Command\PokeApi;

use App\Entity\Fight\Attack;
use App\Entity\Fight\Type;
use App\Service\PokeApi\PokeApiClient;
use App\Service\PokeApi\PokeApiTranslationHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'pokeapi:import:attacks')]
class ImportAttacksCommand extends Command
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
        $list = $this->api->get('move?limit=2000');

        foreach ($list['results'] as $result) {
            $data = $this->api->getByUrl($result['url']);

            $attack = $this->em->getRepository(Attack::class)
                ->findOneBy(['name' => $this->translator->getName($data['names'])])
                ?? new Attack();

            $type = $this->em->getRepository(Type::class)
                ->findOneBy(['name' => $this->translator->getName(
                    $this->api->getByUrl($data['type']['url'])['names']
                )]);

            $name = $this->translator->getName($data['names']);

            $attack
                ->setApiName($data['name'])
                ->setName($name)
                ->setType($type)
                ->setPower($data['power'])
                ->setAccuracy($data['accuracy'])
                ->setPp($data['pp'])
                ->setDamageClass($data['damage_class']['name'])
                ->setPriority($data['priority'])
                ->setDescription(
                    $this->translator->getFlavorText($data['flavor_text_entries'])
                );

            $this->em->persist($attack);
            $output->writeln("✔ Attack: $name");
        }

        $this->em->flush();

        return Command::SUCCESS;
    }
}
