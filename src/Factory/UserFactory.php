<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<User>
 *
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static User|Proxy find(object|array|mixed $criteria)
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy first(string $sortedField = 'id')
 * @method static User|Proxy last(string $sortedField = 'id')
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] all()
 * @method static User[]|Proxy[] findBy(array $attributes)
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create(array|callable $attributes = [])
 */
final class UserFactory extends ModelFactory
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;
    }

    #[ArrayShape(['email' => "string", 'roles' => "array", 'plainPassword' => "string", 'username' => "string", 'createdAt' => "\DateTime", 'updatedAt' => "\DateTime"])]
    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->email(),
            'roles' => [],
            'plainPassword' => 'toto',
            'username' => self::faker()->name(),
            'createdAt' => self::faker()->datetime(),
            'updatedAt' => self::faker()->dateTime()
        ];
    }

    protected function initialize(): self
    {
        return $this
             ->afterInstantiate(function (User $user) {
                 if ($user->getPlainPassword()) {
                     $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
                 }
             })
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
