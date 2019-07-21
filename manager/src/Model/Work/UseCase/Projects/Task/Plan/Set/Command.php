<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Plan\Set;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
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

    public function __construct(int $id)
    {
        $this->id = $id;
    }
}
