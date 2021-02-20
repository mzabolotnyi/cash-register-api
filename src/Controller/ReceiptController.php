<?php

namespace App\Controller;

use App\Entity\Receipt\Receipt;
use App\Service\Receipt\ReceiptManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\Extra\SerializeGroup;

/**
 * @Route("receipt")
 * @SWG\Tag(name="Receipt")
 */
class ReceiptController extends SuperController
{
    /**
     * @Route("/{uuid}", methods={"GET"})
     *
     * @SWG\Get(summary="Get one",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @Model(type=Receipt::class, groups=SerializeGroup::SHORT_DETAIL)
     *     ),
     *     @SWG\Parameter(
     *          name="uuid",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Receipt uuid"
     *     )
     * )
     *
     * @Security("receipt.isCreator(user)")
     *
     * @param Receipt $receipt
     * @return Response
     */
    public function getOne(Receipt $receipt): Response
    {
        return $this->response($receipt, SerializeGroup::SHORT_DETAIL);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @SWG\Post(summary="Create",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @Model(type=Receipt::class, groups={SerializeGroup::SHORT})
     *     )
     * )
     *
     * @param ReceiptManager $receiptManager
     * @return Response
     */
    public function post(ReceiptManager $receiptManager): Response
    {
        $receipt = $receiptManager->create($this->getUser());
        $this->getEm()->flush();

        return $this->response($receipt, SerializeGroup::SHORT);
    }

    /**
     * @Route("/{uuid}/finish", methods={"PUT"})
     *
     * @SWG\Put(summary="Finish",
     *     @SWG\Response(
     *          response=200,
     *          description="OK"
     *     ),
     *     @SWG\Parameter(
     *          name="uuid",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Receipt uuid"
     *     )
     * )
     *
     * @Security("receipt.checkStatus(['PENDING'])")
     * @Security("receipt.isCreator(user)")
     *
     * @param ReceiptManager $receiptManager
     * @param Receipt $receipt
     * @return Response
     */
    public function finish(ReceiptManager $receiptManager, Receipt $receipt): Response
    {
        $receiptManager->finish($receipt);
        $this->getEm()->flush();

        return $this->response();
    }
}