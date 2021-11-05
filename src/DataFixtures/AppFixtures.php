<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\PictureFactory;
use App\Factory\TrickFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        CategoryFactory::new()->createMany(5);
        UserFactory::new()->create(['email' => 'admin@snowtricks.com']);
        UserFactory::new()->createMany(10);

        TrickFactory::new()->createMany(10, function () {
            return [
                'categories' => CategoryFactory::randomRange(0, 5),
                'author' => UserFactory::random()
            ];
        });

        PictureFactory::new()->createMany(100, function () {
            return [
               'trick' => TrickFactory::random()
           ];
        });

        $manager->flush();
    }
}
