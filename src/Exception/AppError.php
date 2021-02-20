<?php

namespace App\Exception;

class AppError
{
    /**@var string */
    private $httpCode;

    /** @var string */
    private $message;

    /** @var array */
    private $formErrors;

    public function __construct(string $httpCode, string $message, array $formErrors = null, string $messageDescription = null)
    {
        $this->httpCode = $httpCode;
        $this->message = $message;
        $this->formErrors = $formErrors;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $data = [
            'code' => $this->httpCode,
            'message' => $this->message,
        ];

        if ($this->formErrors) {
            $data['formErrors'] = $this->formErrors;
        }

        return $data;
    }

    public function getHttpCode(): ?string
    {
        return $this->httpCode;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getFormErrors(): ?array
    {
        return $this->formErrors;
    }
}