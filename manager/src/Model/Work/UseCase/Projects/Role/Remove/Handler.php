<?php

namespace App\Model\Work\UseCase\Projects\Role\Remove;

use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;
use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\RoleRepository;
use DomainException;

class Handler
{
    /** @var RoleRepository */
    private $roles;
    /** @var ProjectRepository */
    private $projects;
    /** @var Flusher */
    private $flusher;

    public function __construct(RoleRepository $roles, ProjectRepository $projects, Flusher $flusher)
    {
        $this->roles = $roles;
        $this->projects = $projects;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $role = $this->roles->get(new Id($command->id));

        if ($this->projects->hasMemberWithRole($role->getId())) {
            throw new DomainException('Role contains member.');
        }

        $this->roles->remove($role);

        $this->flusher->flush();
    }
}
