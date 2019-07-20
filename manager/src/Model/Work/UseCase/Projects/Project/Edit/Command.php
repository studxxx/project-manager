<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Project\Edit;

use App\Model\Work\Entity\Projects\Project\Project;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $name;
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $sort;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromProject(Project $project): self
    {
        $command = new self($project->getId()->getValue());
        $command->name = $project->getName();
        $command->sort = $project->getSort();

        return $command;
    }
}
