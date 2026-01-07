<?php

namespace App\DataFixtures;

use App\Entity\Capture\HuntMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HuntMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $methods = [
            'Rencontres aléatoires',
            'Masuda',
            'Poké Radar',
            'Chaîne SOS',
            'Œuf',
            'Légendes Arceus',
            'Safari',
            'DexNav',
        ];

        foreach ($methods as $name) {
            $method = new HuntMethod();
            $method->setName($name);
            $manager->persist($method);
        }

        $manager->flush();
    }
}
