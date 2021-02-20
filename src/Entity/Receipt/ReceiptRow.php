<?php

namespace App\Entity\Receipt;

use App\Entity\Extra\SuperEntity;
use App\Entity\Product\Product;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use App\Entity\Extra\SerializeGroup;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;
use App\EventListener\Entity\ReceiptRowListener;

/**
 * @ORM\Table(name="receipt_row", uniqueConstraints={
 *        @UniqueConstraint(name="receipt_row_unique_index", columns={"receipt_id", "product_id"})
 * })
 * @ORM\Entity(repositoryClass="App\Repository\Receipt\ReceiptRowRepository")
 * @ORM\EntityListeners({ReceiptRowListener::class})
 */
class ReceiptRow extends SuperEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Receipt\Receipt", inversedBy="rows")
     * @ORM\JoinColumn(nullable=false)
     */
    private $receipt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product\Product")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $vatClass;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $price;

    /**
     * @Assert\GreaterThan(value="0")
     * @ORM\Column(type="integer")
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $count;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $amount;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $amountVat;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $amountDiscount;

    public function __construct()
    {
        parent::__construct();
        $this->price = 0;
        $this->count = 0;
        $this->amount = 0;
        $this->amountVat = 0;
        $this->amountDiscount = 0;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        $this->setVatClass($product->getVatClass());

        return $this;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }

    public function setCount(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountVat(): ?string
    {
        return $this->amountVat;
    }

    public function setAmountVat(string $amountVat): self
    {
        $this->amountVat = $amountVat;

        return $this;
    }

    public function getVatClass(): ?string
    {
        return $this->vatClass;
    }

    public function setVatClass(string $vatClass): self
    {
        $this->vatClass = $vatClass;

        return $this;
    }

    public function getAmountDiscount(): ?string
    {
        return $this->amountDiscount;
    }

    public function setAmountDiscount(string $amountDiscount): self
    {
        $this->amountDiscount = $amountDiscount;

        return $this;
    }
}
