<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Role;

use App\Model\EntityNotFoundException;
use App\Model\Work\Entity\Projects\Project\Project;
use Doctrine\ORM;

class RoleRepository
{
    /** @var ORM\EntityManagerInterface */
    private $em;
    /** @var ORM\EntityRepository */
    private $repository;

    public function __construct(ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Project::class);
    }

    /**
     * @param string $name
     * @return bool
     * @throws ORM\NonUniqueResultException
     */
    public function hasByName(string $name): bool
    {
        return $this->repository->createQueryBuilder('t')
            ->select('COUNT(t.name)')
            ->andWhere('t.name = :name')
            ->setParameter(':name', $name)
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function add(Role $role): void
    {
        $this->em->persist($role);
    }

    public function get(Id $id): Role
    {
        if (!$role = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('Role is not found.');
        }
        return $role;
    }

    public function remove(Role $role): void
    {
        $this->em->remove($role);
    }
}
