<?php

declare(strict_types=1);

namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Exception;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="user_users", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"email"}),
 *      @ORM\UniqueConstraint(columns={"reset_token_token"}),
 * })
 */
class User
{
    public const STATUS_ACTIVE = 'active';
    public const STATUS_BLOCKED = 'blocked';
    public const STATUS_WAIT = 'wait';

    /**
     * @var Id
     * @ORM\Column(type="user_user_id")
     * @ORM\Id()
     */
    private $id;
    /**
     * @var DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    /**
     * @var Email
     * @ORM\Column(type="user_user_email", nullable=true)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="password_hash")
     */
    private $passwordHash;
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="confirm_token")
     */
    private $confirmToken;
    /**
     * @var Name
     * @ORM\Embedded(class="Name")
     */
    private $name;
    /**
     * @var Email|null
     * @ORM\Column(type="user_user_email", nullable=true, name="new_email")
     */
    private $newEmail;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, name="new_email_token")
     */
    private $newEmailToken;
    /**
     * @var string
     * @ORM\Column(type="string", length=16)
     */
    private $status;
    /**
     * @var Role
     * @ORM\Column(type="user_user_role")
     */
    private $role;
    /**
     * @var ResetToken|null
     * @ORM\Embedded(class="ResetToken", columnPrefix="reset_token_")
     */
    private $resetToken;
    /**
     * @var Network[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="Network", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $networks;
    /**
     * @ORM\Version()
     * @ORM\Column(type="integer")
     */
    private $version;

    public function __construct(Id $id, DateTimeImmutable $date, Name $name)
    {
        $this->id = $id;
        $this->date = $date;
        $this->name = $name;
        $this->role = Role::user();
        $this->networks = new ArrayCollection();
    }

    public static function create(Id $id, DateTimeImmutable $date, Name $name, Email $email, string $hash): self
    {
        $user = new self($id, $date, $name);

        $user->email = $email;
        $user->passwordHash = $hash;
        $user->status = self::STATUS_ACTIVE;

        return $user;
    }

    public function edit(Email $email, Name $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    public static function signUpByEmail(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        Email $email,
        string $hash,
        string $confirmToken
    ): User {
        $user = new self($id, $date, $name);

        $user->email = $email;
        $user->passwordHash = $hash;
        $user->confirmToken = $confirmToken;
        $user->status = self::STATUS_WAIT;

        return $user;
    }

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Name $name
     * @param string $network
     * @param string $identity
     * @return User
     * @throws Exception
     */
    public static function signUpByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Name $name,
        string $network,
        string $identity
    ): User {
        $user = new self($id, $date, $name);

        $user->attachNetwork($network, $identity);
        $user->status = self::STATUS_ACTIVE;

        return $user;
    }

    /**
     * @param string $network
     * @param string $identity
     * @throws Exception
     */
    public function attachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isForNetwork($network)) {
                throw new DomainException('Network is already attached.');
            }
        }
        $this->networks->add(new Network($this, $network, $identity));
    }

    public function detachNetwork(string $network, string $identity): void
    {
        foreach ($this->networks as $existing) {
            if ($existing->isFor($network, $identity)) {
                if (!$this->email && $this->networks->count() === 1) {
                    throw new DomainException('Unable to detach the last identity.');
                }
                $this->networks->removeElement($existing);
                return;
            }
        }
        throw new DomainException('Network is not attached.');
    }

    public function requestPasswordReset(ResetToken $token, DateTimeImmutable $date): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if (!$this->email) {
            throw new DomainException('Email is not specified.');
        }
        if ($this->resetToken && !$this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Resetting is already requested.');
        }

        $this->resetToken = $token;
    }

    public function passwordReset(DateTimeImmutable $date, string $hash): void
    {
        if (!$this->resetToken) {
            throw new DomainException('Resetting is not requested.');
        }
        if ($this->resetToken->isExpiredTo($date)) {
            throw new DomainException('Reset token is expired.');
        }
        $this->passwordHash = $hash;
    }

    public function changeName(Name $name): void
    {
        $this->name = $name;
    }

    public function changeRole(Role $role): void
    {
        if ($this->role->isEqual($role)) {
            throw new DomainException('Role is already same.');
        }
        $this->role = $role;
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    public function activate(): void
    {
        if ($this->isActive()) {
            throw new DomainException('User is already active.');
        }
        $this->status = self::STATUS_ACTIVE;
    }

    public function block(): void
    {
        if ($this->isBlocked()) {
            throw new DomainException('User is already blocked.');
        }
        $this->status = self::STATUS_BLOCKED;
    }

    public function confirmSignUp()
    {
        if (!$this->isWait()) {
            throw new DomainException('User is already confirmed.');
        }

        $this->status = self::STATUS_ACTIVE;
        $this->confirmToken = null;
    }

    public function requestEmailChanging(Email $email, string $token)
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->email && $this->email->isEqual($email)) {
            throw new DomainException('Email is already same.');
        }
        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token)
    {
        if (!$this->newEmailToken) {
            throw new DomainException('Changing is not requested.');
        }
        if ($this->newEmailToken !== $token) {
            throw new DomainException('Incorrect changing token.');
        }
        $this->email = $this->newEmail;
        $this->newEmail = null;
        $this->newEmailToken = null;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getConfirmToken(): ?string
    {
        return $this->confirmToken;
    }

    public function getResetToken(): ?ResetToken
    {
        return $this->resetToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getNewEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function getNewEmailToken(): ?string
    {
        return $this->newEmailToken;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return Network[]
     */
    public function getNetworks(): array
    {
        return $this->networks->toArray();
    }

    /**
     * @ORM\PostLoad()
     */
    public function checkEmbeds(): void
    {
        if ($this->resetToken->isEmpty()) {
            $this->resetToken = null;
        }
    }
}
