<?php

namespace App\Exception;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class FormValidationException extends \RuntimeException implements HttpExceptionInterface
{
    /**@var FormInterface */
    private $form;

    /**@var int */
    private $statusCode;

    public function __construct(FormInterface $form)
    {
        $this->statusCode = 422;
        $this->form = $form;
        parent::__construct('Validation error', 0, null);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getForm(): ?FormInterface
    {
        return $this->form;
    }

    public function getHeaders(): array
    {
        return [];
    }
}