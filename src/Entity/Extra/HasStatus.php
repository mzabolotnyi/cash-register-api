<?php

namespace App\Entity\Extra;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait HasStatus
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $status;

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function checkStatus($criteria): bool
    {
        return \is_array($criteria) ? \in_array($this->status, $criteria) : $this->status === $criteria;
    }
}