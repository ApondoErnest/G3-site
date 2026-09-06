<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class MoneyXaf
{
    public function __construct(public int $amount)
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be positive.');
        }
    }

    public function formatted(): string
    {
        return number_format($this->amount, 0, ',', ' ').' FCFA';
    }
}
