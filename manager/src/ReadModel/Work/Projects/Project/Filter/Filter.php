<?php

declare(strict_types=1);

namespace App\ReadModel\Work\Projects\Project\Filter;

use App\Model\Work\Entity\Projects\Project\Status;

class Filter
{
    /** @var string */
    public $member;
    public $name;
    public $status = Status::ACTIVE;

    public function __construct(?string $member)
    {
        $this->member = $member;
    }

    public static function all()
    {
        return new self(null);
    }

    public static function forMember(string $id)
    {
        return new self($id);
    }
}
