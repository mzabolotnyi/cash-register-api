<?php

namespace App\Entity\Extra;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Swagger\Annotations as SWG;

trait UuidableEntity
{
    /**
     * @ORM\Column(type="string", length=36, nullable=false, unique=true)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     * @SWG\Property(type="string")
     */
    protected $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}