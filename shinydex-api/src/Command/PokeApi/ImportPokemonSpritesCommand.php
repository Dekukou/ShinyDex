<?php

namespace App\Command\PokeApi;

use App\Entity\Pokedex\Pokemon;
use App\Entity\Sprite\PokemonSprite;
use App\Service\PokeApi\PokeApiClient;
use App\Service\SpriteImageProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(
    name: 'app:import:pokemon-sprites',
    description: 'Import Pokémon sprites from PokéAPI and store them locally'
)]
class ImportPokemonSpritesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private PokeApiClient $api,
        private SpriteImageProcessor $imageProcessor,
        private string $projectDir
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
        $output->writeln('<info>Importing Pokémon sprites...</info>');

        $pokemons = $this->em->getRepository(Pokemon::class)->findAll();

        foreach ($pokemons as $pokemon) {
            $data = $this->api->get('/pokemon/' . $pokemon->getNameEn());

            $sprites = $data['sprites'];
            if (!$sprites['front_default']) {
                continue;
            }

            $baseDir = sprintf(
                '%s/public/sprites/pokemon/%d',
                $this->projectDir,
                $pokemon->getId()
            );

            if (!is_dir($baseDir)) {
                mkdir($baseDir, 0777, true);
            }

            $spriteEntity = $this->em->getRepository(PokemonSprite::class)
                ->findOneBy(['pokemon' => $pokemon]) ?? new PokemonSprite();

            $spriteEntity->setPokemon($pokemon);

            $spriteEntity->setFrontDefault(
                $this->downloadAndProcess($sprites['front_default'], $baseDir, 'front.png')
            );

            $spriteEntity->setFrontFemale(
                $this->downloadAndProcess($sprites['front_female'], $baseDir, 'front_female.png')
            );

            $spriteEntity->setFrontShiny(
                $this->downloadAndProcess($sprites['front_shiny'], $baseDir, 'front_shiny.png')
            );

            $spriteEntity->setFrontShinyFemale(
                $this->downloadAndProcess($sprites['front_shiny_female'], $baseDir, 'front_shiny_female.png')
            );

            $name = $pokemon->getNameFr();
            $this->em->persist($spriteEntity);
            $output->writeln("✔ Sprite: $name");
            usleep(200_000);
        }

        if (!$dryRun) {
            $this->em->flush();
        }

        $output->writeln('<info>Sprites imported successfully</info>');

        return Command::SUCCESS;
    }

    private function downloadAndProcess(?string $url, string $dir, string $filename): ?string
    {
        if (!$url) {
            return null;
        }

        $path = $dir . '/' . $filename;

        // ✅ Ne rien faire si le fichier existe déjà
        if (file_exists($path)) {
            return str_replace('public/', '', $path);
        }

        try {
            file_put_contents($path, file_get_contents($url));

            try {
                $this->imageProcessor->removeWhiteBackground($path);
            } catch (\Throwable) {
                // silencieux volontairement
            }

            return str_replace('public/', '', $path);
        } catch (\Throwable) {
            return null;
        }
    }
}
