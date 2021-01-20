<?php

declare(strict_types=1);

namespace App\Exception;

class WalletNotFoundException extends \Exception implements TransactionExceptionInterface
{
    public function __construct(int $walletId)
    {
        $message = sprintf('Wallet with id=%s was not found', $walletId);

        parent::__construct($message);
    }
}