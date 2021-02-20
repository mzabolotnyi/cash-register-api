<?php

namespace App\Entity\Receipt;

use App\Entity\Extra\HasStatus;
use App\Entity\Extra\SuperEntity;
use App\Entity\Product\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use App\Entity\Extra\SerializeGroup;

/**
 * @ORM\Table(name="receipt")
 * @ORM\Entity(repositoryClass="App\Repository\Receipt\ReceiptRepository")
 */
class Receipt extends SuperEntity
{
    use HasStatus;

    const STATUS_PENDING = 'PENDING';
    const STATUS_FINISHED = 'FINISHED';

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $finishedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Receipt\ReceiptRow", mappedBy="receipt", orphanRemoval=true)
     * @Serializer\Groups(SerializeGroup::DETAIL)
     */
    private $rows;

    public function __construct()
    {
        parent::__construct();
        $this->status = self::STATUS_PENDING;
        $this->rows = new ArrayCollection();
    }

    /**
     * @return Collection|ReceiptRow[]
     */
    public function getRows(): Collection
    {
        return $this->rows;
    }

    public function addRow(ReceiptRow $row): self
    {
        if (!$this->rows->contains($row)) {
            $this->rows[] = $row;
            $row->setReceipt($this);
        }

        return $this;
    }

    public function removeRow(ReceiptRow $row): self
    {
        if ($this->rows->removeElement($row)) {
            // set the owning side to null (unless already changed)
            if ($row->getReceipt() === $this) {
                $row->setReceipt(null);
            }
        }

        return $this;
    }

    public function getRowByProduct(Product $product): ?ReceiptRow
    {
        $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->eq('product', $product));

        $row = $this->rows->matching($criteria)->first();

        return $row ? $row : null;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\Groups(SerializeGroup::DETAIL)
     * @Serializer\Type("integer")
     */
    public function getAmountTotal()
    {
        $amount = 0;

        foreach ($this->getRows() as $row) {
            $amount += $row->getAmount();
        }

        return $amount;
    }

    /**
     * @Serializer\VirtualProperty()
     * @Serializer\Groups(SerializeGroup::DETAIL)
     * @Serializer\Type("array<array>")
     */
    public function getVatByClass(): array
    {
        $data = [];

        foreach ($this->getRows() as $row) {

            $vatClass = $row->getVatClass();

            if (!array_key_exists($vatClass, $data)) {
                $data[$vatClass] = [
                    'class' => $vatClass,
                    'amount' => 0
                ];
            }

            $data[$vatClass]['amount'] += $row->getAmount();
        }

        return array_values($data);
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(?\DateTimeInterface $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }
}
