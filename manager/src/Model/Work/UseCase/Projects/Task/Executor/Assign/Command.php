<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Executor\Assign;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @var string[]
     * @Assert\NotBlank()
     */
    public $members;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
