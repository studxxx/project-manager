<?php

declare(strict_types=1);

namespace App\Service\Work\Processor;

use App\Service\Work\Processor\Driver\Driver;
use Webmozart\Assert\Assert;

class Processor
{
    /** @var Driver[] */
    private $drivers;

    public function __construct(iterable $drivers)
    {
        Assert::allIsInstanceOf($drivers, Driver::class);
        $this->drivers = $drivers;
    }

    public function process(?string $text): string
    {
        $result = $text;
        foreach ($this->drivers as $driver) {
            $result = $driver->process($result);
        }
        return $result;
    }
}
