<?php

declare(strict_types=1);

namespace App\ReadModel\Work\Projects\Project\Filter;

use App\Model\Work\Entity\Projects\Project\Status;

class Filter
{
    public $name;
    public $status = Status::ACTIVE;
}
