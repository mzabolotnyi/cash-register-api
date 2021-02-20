<?php

namespace App\Form;

use App\Entity\Extra\SuperEntity;
use App\Exception\FormValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;

class FormHandler
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    public function handle(array $data, SuperEntity $entity, string $type, bool $flush = true): void
    {
        $form = $this->formFactory->create($type, $entity);
        $form->submit($data, false);

        if (!$form->isValid()) {
            throw new FormValidationException($form);
        }

        if ($entity->isNew()) {
            $this->em->persist($entity);
        }

        if ($flush) {
            $this->em->flush();
        }
    }
}