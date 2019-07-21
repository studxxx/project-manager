<?php

declare(strict_types=1);

namespace App\Model\Work\Entity\Projects\Task;

use Exception;
use Webmozart\Assert\Assert;

class Id
{
    /** @var string */
    private $value;

    public function __construct(int $value)
    {
        Assert::notEmpty($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
