<?php

namespace App\DataFixtures;

use App\Entity\Pokedex\RegionForm;
use App\Entity\Pokedex\Generation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RegionFormFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $regions = [
            'Alola',
            'Galar',
            'Hisui',
            'Paldea',
        ];

        foreach ($regions as $name) {
            $region = new RegionForm();
            $region->setName($name);
            $manager->persist($region);
        }

        $manager->flush();
    }
}
