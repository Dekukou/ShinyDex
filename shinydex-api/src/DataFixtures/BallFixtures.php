<?php

namespace App\DataFixtures;

use App\Entity\Ball;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BallFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $balls = [

            // 🟥 Série principale (utilisables)
            ['Poké Ball', 1.0, true, false],
            ['Great Ball', 1.5, true, false],
            ['Ultra Ball', 2.0, true, false],
            ['Master Ball', 255.0, true, false],
            ['Premier Ball', 1.0, true, false],
            ['Luxury Ball', 1.0, true, false],
            ['Heal Ball', 1.0, true, false],
            ['Net Ball', 3.5, true, false],
            ['Dive Ball', 3.5, true, false],
            ['Nest Ball', 1.0, true, false],
            ['Repeat Ball', 3.5, true, false],
            ['Timer Ball', 4.0, true, false],
            ['Quick Ball', 5.0, true, false],
            ['Dusk Ball', 3.0, true, false],
            ['Level Ball', 8.0, true, false],
            ['Lure Ball', 5.0, true, false],
            ['Moon Ball', 4.0, true, false],
            ['Friend Ball', 1.0, true, false],
            ['Love Ball', 8.0, true, false],
            ['Heavy Ball', 1.0, true, false],
            ['Fast Ball', 4.0, true, false],
            ['Sport Ball', 1.0, true, false],
            ['Safari Ball', 1.5, true, false],
            ['Dream Ball', 4.0, true, false],
            ['Beast Ball', 5.0, true, false],
            ['Cherish Ball', 1.0, true, false],

            // ⚠️ Inutilisables
            ['Memory Ball', 1.0, false, false],
            ['Strange Ball', 1.0, false, false],

            // 🟣 Légendes Pokémon : Arceus (exclusives)
            ['Heavy Ball (PLA)', 2.0, true, true],
            ['Leaden Ball', 3.0, true, true],
            ['Gigaton Ball', 4.0, true, true],
            ['Feather Ball', 2.0, true, true],
            ['Wing Ball', 3.0, true, true],
            ['Jet Ball', 4.0, true, true],
            ['Origin Ball', 255.0, true, true],
            ['Strange Feather Ball', 1.0, true, true],
            ['Strange Wing Ball', 1.0, true, true],
            ['Strange Jet Ball', 1.0, true, true],
        ];

        foreach ($balls as [$name, $rate, $usable, $plaOnly]) {
            $ball = new Ball();
            $ball->setName($name);
            $ball->setCatchRateBonus($rate);
            $ball->setIsUsable($usable);
            $ball->setIsLegendsArceusOnly($plaOnly);

            $manager->persist($ball);
        }

        $manager->flush();
    }
}
