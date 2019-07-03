<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Detach;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\Flusher;
use Doctrine\ORM\EntityNotFoundException;

class Handler
{
    /** @var UserRepository */
    private $users;
    /** @var Flusher */
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws EntityNotFoundException
     */
    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->user));

        $user->detachNetwork(
            $command->network,
            $command->identity
        );

        $this->flusher->flush();
    }
}
