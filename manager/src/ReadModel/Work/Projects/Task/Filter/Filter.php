<?php

declare(strict_types=1);

namespace App\ReadModel\Work\Projects\Task\Filter;

class Filter
{
    /** @var string|null */
    public $member;
    /** @var string|null */
    public $author;
    /** @var string|null */
    public $project;
    /** @var string|null */
    public $text;
    /** @var string|null */
    public $type;
    /** @var string|null */
    public $status;
    /** @var int|null */
    public $priority;
    /** @var string|null */
    public $executor;
    /** @var string|null */
    public $roots;

    public function __construct(?string $project)
    {
        $this->project = $project;
    }

    public static function all(): self
    {
        return new self(null);
    }

    public static function forProject(string $project): self
    {
        return new self($project);
    }

    public function forMember(string $member): self
    {
        $clone = clone $this;
        $clone->member = $member;

        return $clone;
    }

    public function forAuthor(string $author): self
    {
        $clone = clone $this;
        $clone->author = $author;

        return $clone;
    }

    public function forExecutor(string $executor): self
    {
        $clone = clone $this;
        $clone->executor = $executor;

        return $clone;
    }
}
