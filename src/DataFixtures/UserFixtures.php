<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::new()->create([
            'roles' => [],
            'password' => 'test',
            'name' => 'Antoine Grappin',
            'email' => 'agrappin1@gmail.com',
        ]);

        UserFactory::new()->createMany(20);

        $manager->flush();
    }
}
