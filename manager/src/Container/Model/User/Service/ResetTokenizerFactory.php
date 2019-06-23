<?php

declare(strict_types=1);

namespace App\Container\Model\User\Service;

use App\Model\User\Service\ResetTokenizer;
use DateInterval;

class ResetTokenizerFactory
{
    /**
     * @param string $interval
     * @return ResetTokenizer
     * @throws \Exception
     */
    public function create(string $interval): ResetTokenizer
    {
        return new ResetTokenizer(new DateInterval($interval));
    }
}
