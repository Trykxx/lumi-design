<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CategoryFactory::createMany(5); //cb j'en cree
        ProductFactory::createMany(100, function () //cb j'en cree
         {
            return [
                'category' => CategoryFactory::random(), // Ceci assignera une catégorie aléatoire existante
            ];
        });
    }
}
