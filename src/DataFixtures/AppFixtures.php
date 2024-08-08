<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\OrdersFactory;
use App\Factory\OrderItemFactory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(10);
        CategoryFactory::createMany(5); //cb j'en cree
        ProductFactory::createMany(100, function ()
         {
            return [
                'category' => CategoryFactory::random(), // Ceci assignera une catégorie aléatoire existante
            ];
        });
        OrdersFactory::createMany(10, function () {
            return [
                'customer' => UserFactory::random(),
            ];
        });
        OrderItemFactory::createMany(30, function () {
            return [
                'product' => ProductFactory::random(),
                'orders' => OrdersFactory::random(),
            ];
        });
    }
}
