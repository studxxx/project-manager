<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Mebers\Member\Archive;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
