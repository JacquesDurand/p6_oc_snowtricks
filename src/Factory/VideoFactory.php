<?php

namespace App\Factory;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Video>
 *
 * @method static Video|Proxy createOne(array $attributes = [])
 * @method static Video[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Video|Proxy find(object|array|mixed $criteria)
 * @method static Video|Proxy findOrCreate(array $attributes)
 * @method static Video|Proxy first(string $sortedField = 'id')
 * @method static Video|Proxy last(string $sortedField = 'id')
 * @method static Video|Proxy random(array $attributes = [])
 * @method static Video|Proxy randomOrCreate(array $attributes = [])
 * @method static Video[]|Proxy[] all()
 * @method static Video[]|Proxy[] findBy(array $attributes)
 * @method static Video[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Video[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static VideoRepository|RepositoryProxy repository()
 * @method Video|Proxy create(array|callable $attributes = [])
 */
final class VideoFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'link' => self::faker()->text(),
            'createdAt' => self::faker()->datetime(),
            'updatedAt' => null,
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Video $video) {})
        ;
    }

    protected static function getClass(): string
    {
        return Video::class;
    }
}
