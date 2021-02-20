<?php

namespace App\Repository\Receipt;

use App\Entity\Receipt\Receipt;
use App\Entity\User\User;
use App\Repository\SuperRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Receipt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Receipt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Receipt[]    findAll()
 * @method Receipt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptRepository extends SuperRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Receipt::class);
    }

    public function findPending(User $user): ?Receipt
    {
        return $this->findOneBy(['createdBy' => $user, 'status' => Receipt::STATUS_PENDING]);
    }

    public function getTurnoverByCashRegister(User $user, \DateTime $startDate, \DateTime $endDate): float
    {
        $qb = $this->createQueryBuilder('receipt')
            ->select('SUM(row.amount)')
            ->join('receipt.rows', 'row')
            ->andWhere('receipt.createdBy = :user')
            ->andWhere('receipt.finishedAt BETWEEN :startDate AND :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        return (float)$qb->getQuery()->getSingleScalarResult();
    }
}
