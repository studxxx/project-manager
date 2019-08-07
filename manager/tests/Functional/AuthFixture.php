<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Role;
use App\Model\User\Service\PasswordHasher;
use App\Tests\Builder\User\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class AuthFixture extends Fixture
{
    public const REFERENCE_ADMIN = 'test_auth_admin';
    public const REFERENCE_USER = 'test_auth_user';

    /** @var PasswordHasher */
    private $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function userCredentials(): array
    {
        return [
            'PHP_AUTH_USER' => 'auth-user@app.test',
            'PHP_AUTH_PW' => 'password',
        ];
    }

    public static function adminCredentials(): array
    {
        return [
            'PHP_AUTH_USER' => 'auth-admin@app.test',
            'PHP_AUTH_PW' => 'password',
        ];
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $hash = $this->hasher->hash('password');

        $user = (new UserBuilder())
            ->viaEmail(new Email('auth-user@app.test'), $hash)
            ->confirmed()
            ->build();

        $manager->persist($user);
        $this->setReference(self::REFERENCE_USER, $user);

        $admin = (new UserBuilder())
            ->viaEmail(new Email('auth-admin@app.test'), $hash)
            ->confirmed()
            ->withRole(Role::admin())
            ->build();

        $manager->persist($admin);
        $this->setReference(self::REFERENCE_ADMIN, $admin);

        $manager->flush();
    }
}