<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CategoryFactory::createMany(5); //cb j'en cree
        ProductFactory::createMany(100, function ()
         {
            return [
                'category' => CategoryFactory::random(), // Ceci assignera une catégorie aléatoire existante
            ];
        });
        UserFactory::createMany(10);
    }
}
