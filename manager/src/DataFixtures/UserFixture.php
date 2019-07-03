<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\Role;
use App\Model\User\Entity\User\User;
use App\Model\User\Service\PasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class UserFixture extends Fixture
{
    /** @var PasswordHasher */
    private $hasher;

    public function __construct(PasswordHasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $hash = $this->hasher->hash('123123');

        $network = $this->createSignedUpByNetwork(
            new Name('Dave', 'Black'),
            'facebook',
            '10000000',
        );
        $manager->persist($network);

        $requested = $this->createSignUpRequestedByEmail(
            new Name('Brad', 'Pitt'),
            new Email('requested@app.test'),
            $hash,
        );
        $manager->persist($requested);

        $confirmed = $this->createSignUpConfirmedByEmail(
            new Name('Mike', 'Woo'),
            new Email('user@app.test'),
            $hash,
        );
        $manager->persist($confirmed);

        $admin = $this->createAdminByEmail(
            new Name('John', 'Doe'),
            new Email('admin@app.test'),
            $hash,
        );
        $manager->persist($admin);

        $manager->flush();
    }

    /**
     * @param Name $name
     * @param Email $email
     * @param string $hash
     * @return User
     * @throws Exception
     */
    private function createAdminByEmail(Name $name, Email $email, string $hash): User
    {
        $user = $this->createSignUpConfirmedByEmail($name, $email, $hash);
        $user->changeRole(Role::admin());
        return  $user;
    }

    /**
     * @param Name $name
     * @param Email $email
     * @param string $hash
     * @return User
     * @throws Exception
     */
    private function createSignUpConfirmedByEmail(Name $name, Email $email, string $hash): User
    {
        $user = $this->createSignUpRequestedByEmail($name, $email, $hash);
        $user->confirmSignUp();

        return $user;
    }

    /**
     * @param Name $name
     * @param Email $email
     * @param string $hash
     * @return User
     * @throws Exception
     */
    private function createSignUpRequestedByEmail(Name $name, Email $email, string $hash): User
    {
        return User::signUpByEmail(Id::next(), new \DateTimeImmutable(), $name, $email, $hash, 'token');
    }

    /**
     * @param Name $name
     * @param string $network
     * @param string $identity
     * @return User
     * @throws Exception
     */
    private function createSignedUpByNetwork(Name $name, string $network, string $identity): User
    {
        return User::signUpByNetwork(Id::next(), new \DateTimeImmutable(), $name, $network, $identity);
    }
}
