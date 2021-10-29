<?php

namespace App\Factory;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Picture>
 *
 * @method static Picture|Proxy createOne(array $attributes = [])
 * @method static Picture[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Picture|Proxy find(object|array|mixed $criteria)
 * @method static Picture|Proxy findOrCreate(array $attributes)
 * @method static Picture|Proxy first(string $sortedField = 'id')
 * @method static Picture|Proxy last(string $sortedField = 'id')
 * @method static Picture|Proxy random(array $attributes = [])
 * @method static Picture|Proxy randomOrCreate(array $attributes = [])
 * @method static Picture[]|Proxy[] all()
 * @method static Picture[]|Proxy[] findBy(array $attributes)
 * @method static Picture[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Picture[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PictureRepository|RepositoryProxy repository()
 * @method Picture|Proxy create(array|callable $attributes = [])
 */
final class PictureFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
            'path' => self::faker()->text(),
            'createdAt' => self::faker()->datetime(),
            'updatedAt' => null, // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Picture $picture) {})
        ;
    }

    protected static function getClass(): string
    {
        return Picture::class;
    }
}
