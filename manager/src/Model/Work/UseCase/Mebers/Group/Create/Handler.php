<?php

namespace App\Model\Work\UseCase\Mebers\Group\Create;

use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\Group;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Group\Id;
use Exception;

class Handler
{
    /** @var GroupRepository */
    private $groups;
    /** @var Flusher */
    private $flusher;

    public function __construct(GroupRepository $groups, Flusher $flusher)
    {
        $this->groups = $groups;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        $group = new Group(
            Id::next(),
            $command->name
        );

        $this->groups->add($group);
        $this->flusher->flush();
    }
}
