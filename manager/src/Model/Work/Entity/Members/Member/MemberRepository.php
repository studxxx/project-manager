<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Members\Member;

use App\Model\EntityNotFoundException;
use App\Model\Work\Entity\Members\Group\Id as GroupId;
use Doctrine\ORM;

class MemberRepository
{
    /** @var ORM\EntityManagerInterface */
    private $em;
    /** @var ORM\EntityRepository */
    private $repository;

    public function __construct(ORM\EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(Member::class);
    }

    /**
     * @param Id $id
     * @return bool
     * @throws ORM\NonUniqueResultException
     */
    public function has(Id $id): bool
    {
        return $this->repository->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.id = :id')
            ->setParameter(':id', $id->getValue())
            ->getQuery()
            ->getSingleScalarResult() > 0;
    }

    public function add(Member $member): void
    {
        $this->em->persist($member);
    }

    public function get(Id $id): Member
    {
        if (!$member = $this->repository->find($id->getValue())) {
            throw new EntityNotFoundException('Member is not found.');
        }
        return $member;
    }

    /**
     * @param GroupId $id
     * @return bool
     * @throws ORM\NonUniqueResultException
     */
    public function hasByGroup(GroupId $id): bool
    {
        return $this->repository->createQueryBuilder('t')
                ->select('COUNT(t.id)')
                ->andWhere('t.group = :group')
                ->setParameter(':group', $id->getValue())
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }
}
