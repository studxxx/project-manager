<?php

declare(strict_types=1);

namespace App\Tests\Builder\User;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\User;

class UserBuilder
{
    private $id;
    private $date;

    private $email;
    private $hash;
    private $token;

    private $network;
    private $identity;

    private $confirmed;

    public function __construct()
    {
        $this->id = Id::next();
        $this->date = new \DateTimeImmutable();
    }

    public function viaEmail(Email $email = null, string $hash = null, string $token = null): self
    {
        $clone = clone $this;
        $clone->email = $email ?? new Email('test@mail.com');
        $clone->hash = $hash ?? 'hash';
        $clone->token = $token ?? 'token';

        return $clone;
    }

    public function viaNetwork(string $network = null, string $identity = null): self
    {
        $clone = clone $this;
        $clone->network = $network ?? 'facebook';
        $clone->identity = $identity ?? '00001';

        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;
        return $clone;
    }

    public function build(): User
    {
        $user = null;

        if ($this->email) {
            $user = User::signUpByEmail(
                $this->id,
                $this->date,
                $this->email,
                $this->hash,
                $this->token
            );

            if ($this->confirmed) {
                $user->confirmSignUp();
            }
        }

        if ($this->network) {
            $user = User::signUpByNetwork(
                $this->id,
                $this->date,
                $this->network,
                $this->identity
            );
        }

        return $user;
    }
}
