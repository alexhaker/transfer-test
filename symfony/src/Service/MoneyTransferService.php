<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MoneyTransfer;
use App\Entity\Wallet;
use App\Exception\InsufficientFundsException;
use App\Exception\InvalidAmountException;
use App\Exception\WalletNotFoundException;
use App\Repository\WalletRepository;
use DateTime;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;

class MoneyTransferService
{
    /** @var WalletRepository */
    private $walletRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var float */
    private $transferCommissionPercent;

    public function __construct(
        WalletRepository $walletRepository,
        EntityManagerInterface $entityManager,
        float $transferCommissionPercent
    ) {
        $this->walletRepository = $walletRepository;
        $this->entityManager = $entityManager;
        $this->transferCommissionPercent = $transferCommissionPercent;
    }

    public function transferBetweenWallets(int $sourceWalletId, int $destinationWalletId, int $amount): MoneyTransfer
    {
        if ($amount <= 0) {
            throw new InvalidAmountException($amount);
        }
        $commission = $this->calculateCommission($amount);

        $this->entityManager->beginTransaction();
        try {
            $sourceWallet = $this->walletRepository->find($sourceWalletId, LockMode::PESSIMISTIC_WRITE);
            if (null === $sourceWallet) {
                throw new WalletNotFoundException($sourceWalletId);
            }
            if ($sourceWallet->getAmount() < $amount+$commission) {
                throw new InsufficientFundsException($sourceWallet->getAmount(), $amount+$commission);
            }

            $destinationWallet = $this->walletRepository->find($destinationWalletId, LockMode::PESSIMISTIC_WRITE);
            if (null === $destinationWallet) {
                throw new WalletNotFoundException($destinationWalletId);
            }

            $transfer = $this->createTransfer($sourceWallet, $destinationWallet, $amount, $commission);
            $this->entityManager->persist($transfer);
            $this->entityManager->flush();
            $this->walletRepository->decrementAmount($sourceWalletId, $commission+$amount);
            $this->walletRepository->incrementAmount($destinationWalletId, $amount);
            $this->entityManager->refresh($sourceWallet);
            $this->entityManager->refresh($destinationWallet);

            $this->entityManager->commit();

            return $transfer;
        } catch (Throwable $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }
    }

    private function calculateCommission(int $sum): int
    {
        return (int) round($sum*$this->transferCommissionPercent/100);
    }

    private function createTransfer(
        Wallet $sourceWallet,
        Wallet $destinationWallet,
        int $amount,
        int $commission,
        string $currency = 'USD'
    ) {
        $transfer = new MoneyTransfer();
        $transfer->setSourceWallet($sourceWallet);
        $transfer->setDestinationWallet($destinationWallet);
        $transfer->setAmount($amount);
        $transfer->setCommission($commission);
        $transfer->setCurrency($currency);
        $transfer->setCreatedAt(new DateTime());

        return $transfer;
    }
}