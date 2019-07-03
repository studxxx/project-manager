<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Members\Member;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Email
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Incorrect email.');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }
}
