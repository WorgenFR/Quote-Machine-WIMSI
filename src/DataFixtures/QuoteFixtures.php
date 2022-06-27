<?php

namespace App\DataFixtures;

use App\Factory\QuoteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuoteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        QuoteFactory::new()->createMany(20);
    }
}
