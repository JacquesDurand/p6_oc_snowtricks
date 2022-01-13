<?php

namespace App\Factory;

use App\Entity\Picture;
use App\Repository\PictureRepository;
use JetBrains\PhpStorm\ArrayShape;
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
    #[ArrayShape(['path' => "string", 'alt' => "string", 'createdAt' => "\DateTime", 'updatedAt' => "\DateTime"])]
    protected function getDefaults(): array
    {
        return [
            'path' => str_replace(__DIR__.'/../../public/', '', self::faker()->image(__DIR__.'/../../public/fixtures/pictures', 360, 360, 'animals', true, true, 'cats', true)),
            'alt' => self::faker()->text(32),
            'createdAt' => self::faker()->datetime(),
            'updatedAt' => self::faker()->dateTime()
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Picture $picture) {})
        ;
    }

    protected static function getClass(): string
    {
        return Picture::class;
    }
}
