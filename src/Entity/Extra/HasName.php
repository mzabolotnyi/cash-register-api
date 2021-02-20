<?php

namespace App\Entity\Extra;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Extra\SerializeGroup;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

trait HasName
{
    /**
     * @Assert\Length(max="255")
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups(SerializeGroup::SHORT_DETAIL)
     */
    private $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}