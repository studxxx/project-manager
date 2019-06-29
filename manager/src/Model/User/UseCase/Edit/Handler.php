<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Edit;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Flusher;

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

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $user->edit(
            new Email($command->email),
            new Name(
                $command->firstName,
                $command->lastName
            )
        );

        $this->flusher->flush();
    }
}
