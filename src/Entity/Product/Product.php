<?php

namespace App\Entity\Product;

use App\Entity\Extra\HasName;
use App\Entity\Extra\SoftDeletableEntity;
use App\Entity\Extra\SuperEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use App\Entity\Extra\SerializeGroup;

/**
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="App\Repository\Product\ProductRepository")
 * @UniqueEntity({"barcode"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Product extends SuperEntity
{
    use SoftDeletableEntity;
    use HasName;

    /**
     * @Assert\Length(max="255")
     * @ORM\Column(type="string", length=255, unique=true)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $barcode;

    /**
     * @Assert\GreaterThan(value="0")
     * @ORM\Column(type="decimal", precision=15, scale=3)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $vatClass;

    /**
     * @Assert\GreaterThan(value="1")
     * @ORM\Column(type="integer", nullable=true)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $freeItemFor;

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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

    public function getFreeItemFor(): ?int
    {
        return $this->freeItemFor;
    }

    public function setFreeItemFor(?int $freeItemFor): self
    {
        $this->freeItemFor = $freeItemFor;

        return $this;
    }
}
