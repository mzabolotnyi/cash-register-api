<?php

namespace App\EventListener\Entity;

use App\Entity\Receipt\ReceiptRow;
use App\Service\Receipt\ReceiptManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

class ReceiptRowListener
{
    /** @var ReceiptManager */
    private $receiptManager;

    public function __construct(ReceiptManager $receiptManager)
    {
        $this->receiptManager = $receiptManager;
    }

    /**
     * @ORM\PrePersist()
     * @param ReceiptRow $row
     */
    public function onPrePersist(ReceiptRow $row)
    {
        $this->receiptManager->recalculateRow($row);
    }

    /**
     * @ORM\PreUpdate()
     * @param ReceiptRow $row
     * @param PreUpdateEventArgs $event
     */
    public function onPreUpdate(ReceiptRow $row, PreUpdateEventArgs $event)
    {
        $this->receiptManager->recalculateRow($row);
    }
}