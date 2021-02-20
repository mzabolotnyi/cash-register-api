<?php

namespace App\Controller;

use App\Entity\Product\Product;
use App\Entity\Receipt\Receipt;
use App\Entity\Receipt\ReceiptRow;
use App\Form\Type\ReceiptRowType;
use App\Form\FormHandler;
use App\Service\Receipt\ReceiptManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\Extra\SerializeGroup;

/**
 * @Route("receipt")
 * @SWG\Tag(name="Receipt / Row")
 */
class ReceiptRowController extends SuperController
{
    /**
     * @Route("/{uuid}/row/{barcode}", methods={"POST"})
     *
     * @Entity(name="receipt", expr="repository.findOneByUuid(uuid)")
     * @Entity(name="product", expr="repository.findOneByBarcode(barcode)")
     *
     * @SWG\Post(summary="Add product",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @Model(type=ReceiptRow::class, groups={SerializeGroup::SHORT})
     *     ),
     *     @SWG\Response(
     *          response=422,
     *          description="Validation error"
     *     ),
     *     @SWG\Parameter(
     *          name="uuid",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Receipt uuid"
     *     ),
     *     @SWG\Parameter(
     *          name="barcode",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Product barcode"
     *     )
     * )
     *
     * @Security("receipt.checkStatus(['PENDING'])")
     * @Security("receipt.isCreator(user)")
     *
     * @param ReceiptManager $receiptManager
     * @param Receipt $receipt
     * @param Product $product
     * @return Response
     */
    public function add(ReceiptManager $receiptManager, Receipt $receipt, Product $product): Response
    {
        $row = $receiptManager->addProduct($receipt, $product);
        $this->getEm()->flush();

        return $this->response($row, SerializeGroup::SHORT);
    }

    /**
     * @Route("/row/{uuid}", methods={"PUT"})
     *
     * @SWG\Put(summary="Update",
     *     @SWG\Response(
     *          response=200,
     *          description="OK"
     *     ),
     *     @SWG\Response(
     *          response=422,
     *          description="Validation error"
     *     ),
     *     @SWG\Parameter(
     *          name="uuid",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Row uuid"
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          type="string",
     *          required=true,
     *          @Model(type=ReceiptRowType::class)
     *     )
     * )
     *
     * @Security("row.getReceipt().checkStatus(['PENDING'])")
     * @Security("row.getReceipt().isCreator(user)")
     *
     * @param Request $request
     * @param FormHandler $formHandler
     * @param ReceiptRow $row
     * @return Response
     */
    public function put(Request $request, FormHandler $formHandler, ReceiptRow $row): Response
    {
        $formHandler->handle($request->request->all(), $row, ReceiptRowType::class);

        return $this->response();
    }

    /**
     * @Route("/row/{uuid}", methods={"DELETE"})
     *
     * @SWG\Delete(summary="Delete",
     *     @SWG\Response(
     *          response=200,
     *          description="OK"
     *     ),
     *     @SWG\Parameter(
     *          name="uuid",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Row uuid"
     *     )
     * )
     *
     * @param ReceiptRow $row
     * @return Response
     */
    public function delete(ReceiptRow $row): Response
    {
        $this->getEm()->remove($row);
        $this->getEm()->flush();

        return $this->response();
    }
}