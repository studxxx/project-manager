<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Project\Department\Remove;

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
    public $id;

    public function __construct(string $project, string $id)
    {
        $this->project = $project;
        $this->id = $id;
    }
}
