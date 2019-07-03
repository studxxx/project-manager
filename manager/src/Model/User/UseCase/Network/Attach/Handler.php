<?php

declare(strict_types=1);

namespace App\Model\User\UseCase\Network\Attach;

use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\Flusher;
use Doctrine\ORM;
use DomainException;
use Exception;

class Handler
{
    private $users;
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    /**
     * @param Command $command
     * @throws ORM\EntityNotFoundException
     * @throws ORM\NonUniqueResultException
     * @throws Exception
     */
    public function handle(Command $command): void
    {
        if ($this->users->hasByNetworkIdentity($command->network, $command->identity)) {
            throw new DomainException('Profile is already in use.');
        }

        $user = $this->users->get(new Id($command->user));

        $user->attachNetwork($command->network, $command->identity);

        $this->flusher->flush();
    }
}
