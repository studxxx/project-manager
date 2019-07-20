<?php

declare(strict_types=1);

namespace App\DataFixtures\Work\Members;

use App\Model\Work\Entity\Members\Group\Group;
use App\Model\Work\Entity\Members\Group\Id;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class GroupFixture extends Fixture
{
    public const REFERENCE_STAFF = 'work_member_group_staff';
    public const REFERENCE_CUSTOMERS = 'work_member_group_customers';

    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $staff = new Group(
            Id::next(),
            'Our staff'
        );

        $manager->persist($staff);
        $this->setReference(self::REFERENCE_STAFF, $staff);

        $customer = new Group(
            Id::next(),
            'Customers'
        );

        $manager->persist($customer);
        $this->setReference(self::REFERENCE_CUSTOMERS, $customer);

        $manager->flush();
    }
}
