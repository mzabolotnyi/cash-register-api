<?php

namespace App\Repository\Receipt;

use App\Entity\Receipt\ReceiptRow;
use App\Repository\SuperRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReceiptRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReceiptRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReceiptRow[]    findAll()
 * @method ReceiptRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReceiptRowRepository extends SuperRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReceiptRow::class);
    }
}
