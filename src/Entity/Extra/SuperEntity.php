<?php

namespace App\Entity\Extra;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

abstract class SuperEntity
{
    use TimestampableEntity,
        BlameableEntity,
        UuidableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        $this->uuid = Uuid::uuid4()->toString();
    }

    public function __toString(): string
    {
        return $this->getUuid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isNew(): bool
    {
        return null === $this->getId();
    }

    public function match(SuperEntity $entity): bool
    {
        return $this->uuid === $entity->getUuid();
    }
}