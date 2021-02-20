<?php

namespace App\Controller;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserInterface|User|null getUser()
 */
class SuperController extends AbstractFOSRestController
{
    /**
     * Generate response
     *
     * Pass any data you want to return in response. If data is null and code not set method will generate OK response with 200 http code
     * If you use serialization groups you can pass array of needed groups or name of single group
     * Also you can pass custom headers that will be added to response
     *
     * @param $payload
     * @param array|null $groups
     * @param array|string $headers
     * @param int $statusCode
     * @return Response
     */
    protected function response($payload = null, $groups = null, int $statusCode = Response::HTTP_OK, array $headers = []): Response
    {
        if (\is_string($groups)) {
            $groups = [$groups];
        }

        $data = [
            'code' => $statusCode,
            'message' => 'OK',
            'payload' => $payload ?? 'OK',
        ];

        $view = $this->view($data, $statusCode, $headers);

        if (!empty($groups)) {
            $context = new Context();
            $context->setGroups($groups);
            $view->setContext($context);
        }

        return $this->handleView($view);
    }

    protected function getEm(): ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }
}