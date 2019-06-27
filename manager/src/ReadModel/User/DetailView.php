<?php

declare(strict_types=1);

namespace App\ReadModel\User;

class DetailView
{
    public $id;
    public $date;
    public $name_first;
    public $name_last;
    public $email;
    public $role;
    public $status;
    /** @var NetworkView[] */
    public $networks;

    public function getFull()
    {
        return $this->name_first . ' ' . $this->name_last;
    }
}
