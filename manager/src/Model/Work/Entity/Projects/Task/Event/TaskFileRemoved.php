<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Task\Event;

use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Projects\Task\File\Info;
use App\Model\Work\Entity\Projects\Task\Id;
use App\Model\Work\Entity\Projects\Task\File\Id as FileId;

class TaskFileRemoved
{
    /** @var MemberId */
    public $actorId;
    /** @var Id */
    public $taskId;
    /** @var FileId */
    public $fileId;
    /** @var Info */
    public $info;

    public function __construct(MemberId $actorId, Id $taskId, FileId $fileId, Info $info)
    {
        $this->actorId = $actorId;
        $this->taskId = $taskId;
        $this->fileId = $fileId;
        $this->info = $info;
    }
}
