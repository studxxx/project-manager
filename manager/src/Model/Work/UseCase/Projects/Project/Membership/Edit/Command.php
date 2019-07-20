<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Project\Membership\Edit;

use App\Model\Work\Entity\Projects\Project\Department\Department;
use App\Model\Work\Entity\Projects\Project\Membership;
use App\Model\Work\Entity\Projects\Project\Project;
use App\Model\Work\Entity\Projects\Role\Role;
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

    public function __construct(string $project, string $member)
    {
        $this->project = $project;
        $this->member = $member;
    }

    public static function fromMembership(Project $project, Membership $membership): self
    {
        $command = new self($project->getId()->getValue(), $membership->getMember()->getId()->getValue());

        $command->departments = array_map(static function (Department $department): string {
            return $department->getId()->getValue();
        }, $membership->getDepartments());
        $command->roles = array_map(static function (Role $role): string {
            return $role->getId()->getValue();
        }, $membership->getRoles());

        return $command;
    }
}
