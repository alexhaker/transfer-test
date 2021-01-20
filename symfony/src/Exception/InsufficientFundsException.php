<?php

declare(strict_types=1);

namespace App\Exception;

class InsufficientFundsException extends \Exception implements TransactionExceptionInterface
{
    public function __construct(int $walletAmount, int $requiredAmount)
    {
        $message = sprintf(
            'Insufficient funds to perform transaction. Required - %s USD, current - %s USD',
            round($requiredAmount/100, 2),
            round($walletAmount/100, 2)
        );

        parent::__construct($message);
    }
}