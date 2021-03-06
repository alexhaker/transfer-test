<?php

namespace App\Repository;

use App\Entity\MoneyTransfer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoneyTransfer|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoneyTransfer|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoneyTransfer[]    findAll()
 * @method MoneyTransfer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoneyTransfer::class);
    }
}
