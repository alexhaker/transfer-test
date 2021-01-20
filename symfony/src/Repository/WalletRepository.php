<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wallet[]    findAll()
 * @method Wallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wallet::class);
    }

    public function incrementAmount(int $walletId, int $sum)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update('App\Entity\Wallet', 'w');
        $qb->where('w.id = :walletId');
        $qb->set('w.amount', $qb->expr()->sum('w.amount', $sum));
        $qb->setParameter('walletId', $walletId);

        return $qb->getQuery()->execute();
    }

    public function decrementAmount(int $walletId, int $sum)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update('App\Entity\Wallet', 'w');
        $qb->where('w.id = :walletId');
        $qb->set('w.amount', $qb->expr()->diff('w.amount', $sum));
        $qb->setParameter('walletId', $walletId);

        return $qb->getQuery()->execute();
    }
}
