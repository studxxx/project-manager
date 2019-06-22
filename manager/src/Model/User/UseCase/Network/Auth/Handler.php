<?php

namespace App\Model\User\UseCase\Network\Auth;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;
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
        if ($this->users->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new \DomainException('User already exists.');
        }

        $user = new User(
            Id::next(),
            new \DateTimeImmutable()
        );

        $user->signUpByNetwork(
            $command->network,
            $command->identity
        );

        $this->users->add($user);
        $this->flusher->flush();
    }
}
