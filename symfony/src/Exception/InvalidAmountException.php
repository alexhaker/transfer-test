<?php

declare(strict_types=1);

namespace App\Exception;

class InvalidAmountException extends \Exception implements TransactionExceptionInterface
{
    public function __construct(int $amount)
    {
        $message = sprintf('Value of transaction amount (%s) should be greater then 0', $amount);
        parent::__construct($message);
    }
}