<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Task\Event;

use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Projects\Task\Id;

class TaskEdited
{
    /** @var MemberId */
    public $actorId;
    /** @var Id */
    public $taskId;
    /** @var string */
    public $name;
    /** @var string */
    public $content;

    public function __construct(MemberId $actorId, Id $taskId, string $name, string $content)
    {
        $this->actorId = $actorId;
        $this->taskId = $taskId;
        $this->name = $name;
        $this->content = $content;
    }
}
