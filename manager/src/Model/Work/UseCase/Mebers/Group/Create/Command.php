<?php

namespace App\Model\Work\UseCase\Mebers\Group\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
}
