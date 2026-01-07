<?php

namespace App\DataFixtures;

use App\Entity\Capture\Ball;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BallFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $balls = [
            // =========================
            // Balls classiques
            // =========================
            ['Poké Ball', true, false],
            ['Super Ball', true, false],
            ['Hyper Ball', true, false],
            ['Master Ball', true, false],
            ['Safari Ball', true, false],
            ['Filet Ball', true, false],
            ['Faiblo Ball', true, false],
            ['Rapide Ball', true, false],
            ['Chrono Ball', true, false],
            ['Honor Ball', true, false],
            ['Luxe Ball', true, false],
            ['Copain Ball', true, false],
            ['Appât Ball', true, false],
            ['Niveau Ball', true, false],
            ['Masse Ball', true, false],
            ['Speed Ball', true, false],
            ['Sombre Ball', true, false],
            ['Rêve Ball', true, false],

            // Non utilisables
            ['Mémoire Ball', false, false],
            ['Étrange Ball', false, false],

            // =========================
            // Pokémon Legends Arceus
            // =========================
            ['Poké Ball (Hisui)', true, true],
            ['Super Ball (Hisui)', true, true],
            ['Hyper Ball (Hisui)', true, true],
            ['Méga Ball (Hisui)', true, true],
            ['Gigaton Ball', true, true],
            ['Ultra Ball (Hisui)', true, true],
            ['Méga Ball Lourde', true, true],
            ['Gigaton Lourde', true, true],
            ['Ultra Lourde', true, true],
            ['Jet Ball', true, true],
        ];

        foreach ($balls as [$name, $isUsable, $isLegendArceus]) {
            $ball = new Ball();
            $ball->setName($name);
            $ball->setIsUsable($isUsable);
            $ball->setIsLegendArceus($isLegendArceus);

            $manager->persist($ball);
        }

        $manager->flush();
    }
}
