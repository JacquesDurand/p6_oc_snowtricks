<?php

namespace App\Factory;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use JetBrains\PhpStorm\ArrayShape;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Trick>
 *
 * @method static Trick|Proxy createOne(array $attributes = [])
 * @method static Trick[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Trick|Proxy find(object|array|mixed $criteria)
 * @method static Trick|Proxy findOrCreate(array $attributes)
 * @method static Trick|Proxy first(string $sortedField = 'id')
 * @method static Trick|Proxy last(string $sortedField = 'id')
 * @method static Trick|Proxy random(array $attributes = [])
 * @method static Trick|Proxy randomOrCreate(array $attributes = [])
 * @method static Trick[]|Proxy[] all()
 * @method static Trick[]|Proxy[] findBy(array $attributes)
 * @method static Trick[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Trick[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TrickRepository|RepositoryProxy repository()
 * @method Trick|Proxy create(array|callable $attributes = [])
 */
final class TrickFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    #[ArrayShape(['title' => "string", 'slug' => "string", 'state' => "string", 'description' => "string", 'createdAt' => "\DateTime", 'updatedAt' => "\DateTime"])]
    protected function getDefaults(): array
    {
        return [
            'title' => self::faker()->text(50),
            'state' => self::faker()->text(),
            'description' => self::faker()->paragraph(4),
            'createdAt' => self::faker()->datetime(),
            'updatedAt' => self::faker()->dateTime()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Trick $trick) {})
        ;
    }

    protected static function getClass(): string
    {
        return Trick::class;
    }
}
