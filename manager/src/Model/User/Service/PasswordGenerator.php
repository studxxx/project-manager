<?php

declare(strict_types=1);

namespace App\Model\User\Service;

use Exception;
use Ramsey\Uuid\Uuid;

class PasswordGenerator
{
    /**
     * @return string
     * @throws Exception
     */
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
