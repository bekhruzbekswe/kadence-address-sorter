<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class InvalidAddressException extends Exception
{
    public static function forAddress(string $address, string $reason): self
    {
        return new self(sprintf('Invalid address "%s": %s', $address, $reason));
    }
}