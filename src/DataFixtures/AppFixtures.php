<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\TrickFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        CategoryFactory::new()->createMany(5);
        UserFactory::new()->createMany(10);

        TrickFactory::new()->createMany(10, function () {
            return [
                'categories' => CategoryFactory::randomRange(0,5),
                'author' => UserFactory::random()
            ];
        });

        $manager->flush();
    }
}
