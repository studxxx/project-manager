<?php

declare(strict_types=1);

namespace App\DataFixtures\Work\Projects;

use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\Permission;
use App\Model\Work\Entity\Projects\Role\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class RoleFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $guest = $this->createRole('Guest', []);
        $manager->persist($guest);

        $manage = $this->createRole('Manager', [
            Permission::MANAGE_PROJECT_MEMBERS
        ]);
        $manager->persist($manage);

        $manager->flush();
    }

    /**
     * @param string $name
     * @param array $permissions
     * @return Role
     * @throws Exception
     */
    private function createRole(string $name, array $permissions): Role
    {
        return new Role(Id::next(), $name, $permissions);
    }
}
