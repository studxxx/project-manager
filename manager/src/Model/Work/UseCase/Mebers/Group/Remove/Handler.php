<?php

namespace App\Model\Work\UseCase\Mebers\Group\Remove;

use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Group\Id;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use Doctrine\ORM;
use DomainException;

class Handler
{
    /** @var GroupRepository */
    private $groups;
    /** @var MemberRepository */
    private $members;
    /** @var Flusher */
    private $flusher;

    public function __construct(GroupRepository $groups, MemberRepository $members, Flusher $flusher)
    {
        $this->groups = $groups;
        $this->members = $members;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws ORM\NonUniqueResultException
     */
    public function handle(Command $command): void
    {
        $group = $this->groups->get(new Id($command->id));

        if ($this->members->hasByGroup($group->getId())) {
            throw new DomainException('Group is not empty.');
        }

        $this->groups->remove($group);
        $this->flusher->flush();
    }
}
