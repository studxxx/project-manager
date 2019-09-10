<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Task\Event;

use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Projects\Task\Id;
use App\Model\Work\Entity\Projects\Task\Type;

class TaskTypeChanged
{
    /** @var MemberId */
    public $actorId;
    /** @var Id */
    public $taskId;
    /** @var Type */
    public $type;

    public function __construct(MemberId $actorId, Id $taskId, Type $type)
    {
        $this->actorId = $actorId;
        $this->taskId = $taskId;
        $this->type = $type;
    }
}
