<?php

namespace App\DataFixtures;

use App\Entity\HuntMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HuntMethodFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $methods = [
            'Masuda Method',
            'Soft Reset',
            'Poké Radar',
            'Chain Fishing',
            'Random Encounter',
            'Egg Breeding',
            'Mass Outbreak',
            'Sandwich Method',
        ];

        foreach ($methods as $name) {
            $method = new HuntMethod();
            $method->setName($name);
            $manager->persist($method);
        }

        $manager->flush();
    }
}
