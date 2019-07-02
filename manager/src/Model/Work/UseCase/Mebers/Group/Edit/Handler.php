<?php

namespace App\Model\Work\UseCase\Mebers\Group\Edit;

use App\Model\Flusher;
use App\Model\Work\Entity\Members\Group\GroupRepository;
use App\Model\Work\Entity\Members\Group\Id;

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

    public function handle(Command $command): void
    {
        $group = $this->groups->get(new Id($command->id));
        $group->edit($command->name);

        $this->flusher->flush();
    }
}
