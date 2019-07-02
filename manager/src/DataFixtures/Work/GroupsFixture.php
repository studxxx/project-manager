<?php

declare(strict_types=1);

namespace App\DataFixtures\Work;

use App\Model\Work\Entity\Members\Group\Group;
use App\Model\Work\Entity\Members\Group\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class GroupsFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $group = new Group(
            Id::next(),
            'Our staff'
        );

        $manager->persist($group);

        $group = new Group(
            Id::next(),
            'Customers'
        );

        $manager->persist($group);

        $manager->flush();
    }
}
