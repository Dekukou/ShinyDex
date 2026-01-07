<?php

namespace App\DataFixtures;

use App\Entity\Pokedex\RegionForm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFormFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $regions = [
            'Kanto',
            'Johto',
            'Hoenn',
            'Sinnoh',
            'Unys',
            'Kalos',
            'Alola',
            'Galar',
            'Paldea',
            'Hisui',
        ];

        foreach ($regions as $name) {
            $region = new RegionForm();
            $region->setName($name);
            $manager->persist($region);
        }

        $manager->flush();
    }
}
