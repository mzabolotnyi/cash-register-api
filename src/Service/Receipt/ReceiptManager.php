<?php

namespace App\Service\Receipt;

use App\Entity\Product\Product;
use App\Entity\Receipt\Receipt;
use App\Entity\Receipt\ReceiptRow;
use App\Entity\User\User;
use App\Repository\Receipt\ReceiptRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReceiptManager
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var VatCalculator */
    private $vatCalculator;

    /** @var ReceiptRepository */
    private $receiptRepository;

    public function __construct(EntityManagerInterface $em, VatCalculator $vatCalculator)
    {
        $this->em = $em;
        $this->vatCalculator = $vatCalculator;
        $this->receiptRepository = $em->getRepository(Receipt::class);
    }

    public function create(User $user): Receipt
    {
        $receipt = $this->receiptRepository->findPending($user);

        if ($receipt === null) {
            $receipt = new Receipt();
            $this->em->persist($receipt);
        } else {
            $receipt->getRows()->clear();
        }

        return $receipt;
    }

    public function addProduct(Receipt $receipt, Product $product): ReceiptRow
    {
        $row = $receipt->getRowByProduct($product);

        if ($row) {

            $newCount = $row->getCount() + 1;
            $row->setCount($newCount);

        } else {

            $row = new ReceiptRow();
            $row->setReceipt($receipt)
                ->setProduct($product)
                ->setPrice($product->getPrice())
                ->setCount(1);

            $this->em->persist($row);
        }

        return $row;
    }

    public function finish(Receipt $receipt): void
    {
        $receipt->setStatus(Receipt::STATUS_FINISHED)
            ->setFinishedAt(new \DateTime());
    }

    public function recalculateRow(ReceiptRow $row): void
    {
        $amount = round($row->getPrice() * $row->getCount(), 2);
        $amountVat = $this->vatCalculator->calculate($amount, $row->getProduct()->getVatClass());

        $row->setAmount($amount)
            ->setAmountVat($amountVat);
    }
}