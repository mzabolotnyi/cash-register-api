<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Repository\Receipt\ReceiptRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * @Route("analyst")
 * @SWG\Tag(name="Analyst")
 */
class AnalystController extends SuperController
{
    /**
     * @Route("/{username}", methods={"GET"})
     *
     * @SWG\Get(summary="Get turnover by cash register in period",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @SWG\Schema(type="object",
     *              @SWG\Property(property="amount", type="string")
     *          )
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Period not valid"
     *     ),
     *     @SWG\Parameter(
     *          name="username",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Cash register username"
     *     ),
     *     @SWG\Parameter(
     *          name="startDate",
     *          in="query",
     *          type="string",
     *          required=true,
     *          description="Start date (YYYY-MM-DD HH:MM:SS)"
     *     ),
     *     @SWG\Parameter(
     *          name="endDate",
     *          in="query",
     *          type="string",
     *          required=true,
     *          description="End date (YYYY-MM-DD HH:MM:SS)"
     *     ),
     * )
     *
     * @param Request $request
     * @param User $user
     * @param ReceiptRepository $receiptRepository
     * @return Response
     */
    public function getTurnoverByCashRegister(Request $request, User $user, ReceiptRepository $receiptRepository): Response
    {
        $startDate = \DateTime::createFromFormat('Y-m-d H:i:s', $request->get('startDate'));
        $endDate = \DateTime::createFromFormat('Y-m-d H:i:s', $request->get('endDate'));

        if (!$startDate || !$endDate) {
            throw new BadRequestHttpException('Period not valid');
        }

        $payload = [
            'amount' => $receiptRepository->getTurnoverByCashRegister($user, $startDate, $endDate)
        ];

        return $this->response($payload);
    }
}