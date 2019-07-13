<?php

namespace App\Model\Work\UseCase\Projects\Role\Copy;

use App\Model\Flusher;
use App\Model\Work\Entity\Projects\Role\Id;
use App\Model\Work\Entity\Projects\Role\RoleRepository;
use Doctrine\ORM\NonUniqueResultException;
use DomainException;
use Exception;

class Handler
{
    /** @var RoleRepository */
    private $roles;
    /** @var Flusher */
    private $flusher;

    public function __construct(RoleRepository $roles, Flusher $flusher)
    {
        $this->roles = $roles;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws NonUniqueResultException
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        $current = $this->roles->get(new Id($command->id));

        if ($this->roles->hasByName($command->name)) {
            throw new DomainException('Role is already exists.');
        }

        $role = $current->clone(Id::next(), $command->name);
        $this->roles->add($role);

        $this->flusher->flush();
    }
}
