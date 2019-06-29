<?php

declare(strict_types=1);

namespace App\Model\User\Email\Confirm;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @var string
     */
    public $token;

    public function __construct(string $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }
}