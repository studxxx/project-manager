<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Plan\Set;

use App\Model\Work\Entity\Projects\Task\Task;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $actor;
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @var DateTimeImmutable
     * @Assert\Date()
     */
    public $date;

    public function __construct(string $actor, int $id)
    {
        $this->actor = $actor;
        $this->id = $id;
    }

    public static function fromTask(string $actor, Task $task)
    {
        $command = new self($actor, $task->getId()->getValue());
        $command->date = $task->getPlanDate() ?: new DateTimeImmutable();

        return $command;
    }
}
