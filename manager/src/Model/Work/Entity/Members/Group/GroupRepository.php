<?php

namespace App\Model\Work\Entity\Members\Group;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class GroupRepository
{
    /** @var EntityManagerInterface */
    private $em;
    private $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Group::class);
    }

    public function add(Group $group)
    {
        $this->em->persist($group);
    }

    public function get(Id $id): Group
    {
        if (!$group = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('Group is not found.');
        }
        return $group;
    }

    public function remove(Group $group)
    {
        $this->em->remove($group);
    }
}
