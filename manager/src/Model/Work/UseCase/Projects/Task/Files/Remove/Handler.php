<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Files\Remove;

use App\Model\Flusher;
use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use App\Model\Work\Entity\Projects\Task\Id;
use App\Model\Work\Entity\Projects\Task\TaskRepository;
use App\Model\Work\Entity\Projects\Task\File\Id as FileId;

class Handler
{
    /** @var MemberRepository */
    private $members;
    /** @var TaskRepository */
    private $tasks;
    /** @var Flusher */
    private $flusher;

    public function __construct(MemberRepository $members, TaskRepository $tasks, Flusher $flusher)
    {
        $this->members = $members;
        $this->tasks = $tasks;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
//        $actor = $this->members->get(new MemberId($command->actor));
        $task = $this->tasks->get(new Id($command->id));

        $task->removeFile(new FileId($command->file));

        $this->flusher->flush();
    }
}