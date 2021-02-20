<?php

namespace App\EventListener\Kernel;

use App\Exception\FormValidationException;
use App\Exception\AppError;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use OAuth2\OAuth2ServerException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ViewHandlerInterface $viewHandler, LoggerInterface $exceptionLogger)
    {
        $this->viewHandler = $viewHandler;
        $this->logger = $exceptionLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $acceptableTypes = $event->getRequest()->getAcceptableContentTypes();
        if (count($acceptableTypes) > 0 && strpos($acceptableTypes[0], 'html')) {
            return;
        }

        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $appError = $this->handleHttp($exception);
        } elseif ($exception instanceof OAuth2ServerException) {
            $appError = $this->handleOauth2Server($exception);
        } elseif ($exception instanceof FormValidationException) {
            $appError = $this->handleFormValidation($exception);
        } else {
            $appError = $this->handleInternal($exception);
        }

        $view = View::create($appError->getData(), $appError->getHttpCode());
        $event->setResponse($this->viewHandler->handle($view));
    }

    private function handleHttp(HttpException $exception): AppError
    {
        return new AppError($exception->getStatusCode(), $exception->getMessage());
    }

    private function handleOauth2Server(OAuth2ServerException $exception): AppError
    {
        return new AppError($exception->getHttpCode(), $exception->getDescription());
    }

    private function handleFormValidation(FormValidationException $exception): AppError
    {
        return new AppError(
            $exception->getStatusCode(),
            $exception->getMessage(),
            $this->createFormErrorsArray($exception->getForm())
        );
    }

    private function handleInternal(\Throwable $exception): AppError
    {
        $message = sprintf(
            'Uncaught PHP Exception %s: "%s" at %s line %s',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );

        $this->logger->error($message);

        if (getenv('APP_ENV') !== 'dev') {
            $message = 'server_error';
        }

        return new AppError(500, $message);
    }

    public function createFormErrorsArray(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->createFormErrorsArray($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    private function generateErrorMessage(\Exception $exception): string
    {
        return sprintf(
            'Uncaught PHP Exception %s: "%s" at %s line %s',
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        );
    }
}