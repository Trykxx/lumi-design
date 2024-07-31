<?php

namespace App\Factory;

use App\Entity\Product;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

// nouvelle maniere de faire des fixtures,
// make factory = fais les fixtures par rapport a l'entité

/**
 * @extends PersistentProxyObjectFactory<Product>
 */
final class ProductFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        self::faker()->addProvider(new \Smknstd\FakerPicsumImages\FakerPicsumImagesProvider(self::faker()));
    }

    public static function class(): string
    {
        return Product::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'updatedAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-1 year')),
            'name' => self::faker()->text(10),
            'description' => self::faker()->text(255),
            'image' => self::faker()->imageUrl(width: 800, height: 600),
            'slug' => self::faker()->text(255),
            'stock' => self::faker()->numberBetween(0,1000),
            'price' => self::faker()->randomFloat(2, 5, 1000),
            'category' => CategoryFactory::new() // reference
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Product $product): void {})
        ;
    }
}