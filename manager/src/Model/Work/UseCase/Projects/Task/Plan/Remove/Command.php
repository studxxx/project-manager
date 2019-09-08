<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Plan\Remove;

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

    public function __construct(string $actor, int $id)
    {
        $this->actor = $actor;
        $this->id = $id;
    }
}
