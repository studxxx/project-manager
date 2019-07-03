<?php

declare(strict_types=1);

namespace App\DataFixtures\Work;

use App\DataFixtures\UserFixture;
use App\Model\User\Entity\User\User;
use App\Model\Work\Entity\Members\Group\Group;
use App\Model\Work\Entity\Members\Member\Email;
use App\Model\Work\Entity\Members\Member\Id;
use App\Model\Work\Entity\Members\Member\Member;
use App\Model\Work\Entity\Members\Member\Name;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MemberFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /**
         * @var User $admin
         * @var User $user
         */
        $admin = $this->getReference(UserFixture::REFERENCE_ADMIN);
        $user = $this->getReference(UserFixture::REFERENCE_USER);

        /**
         * @var Group $staff
         * @var Group $customer
         */
        $staff = $this->getReference(GroupsFixture::REFERENCE_STAFF);
        $customer = $this->getReference(GroupsFixture::REFERENCE_CUSTOMERS);

        $member = $this->createMember($admin, $staff);
        $manager->persist($member);

        $member = $this->createMember($user, $customer);
        $manager->persist($member);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            GroupsFixture::class,
        ];
    }

    private function createMember(User $user, Group $group): Member
    {
        return new Member(
            new Id($user->getId()->getValue()),
            $group,
            new Name(
                $user->getName()->getFirst(),
                $user->getName()->getLast()
            ),
            new Email($user->getEmail() ? $user->getEmail()->getValue() : null)
        );
    }
}
