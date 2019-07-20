<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Project\Membership\Add;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $project;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $member;
    /**
     * @var array|string[]
     * @Assert\NotBlank()
     */
    public $departments;
    /**
     * @var array
     * @Assert\NotBlank()
     */
    public $roles;

    public function __construct(string $project)
    {
        $this->project = $project;
    }
}
