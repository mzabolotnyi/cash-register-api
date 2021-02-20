<?php

namespace App\Controller;

use App\Entity\Product\Product;
use App\Repository\Product\ProductRepository;
use App\Form\Type\ProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\Extra\SerializeGroup;
use App\Form\FormHandler;

/**
 * @Route("product")
 * @SWG\Tag(name="Product")
 */
class ProductController extends SuperController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @SWG\Get(summary="Get list",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref=@Model(type=Product::class, groups={SerializeGroup::SHORT}))
     *          )
     *     ),
     *     @SWG\Parameter(
     *          name="pagination[limit]",
     *          in="query",
     *          type="string",
     *          required=false
     *     ),
     *     @SWG\Parameter(
     *          name="pagination[page]",
     *          in="query",
     *          type="string",
     *          required=false
     *     )
     * )
     *
     * @param ProductRepository $repository
     * @param Request $request
     * @return Response
     */
    public function getList(Request $request, ProductRepository $repository): Response
    {
        $payload = $repository->findByParams($request->query->all());

        return $this->response($payload, SerializeGroup::SHORT);
    }

    /**
     * @Route("/{barcode}", methods={"GET"})
     *
     * @SWG\Get(summary="Get one",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @Model(type=Product::class, groups=SerializeGroup::SHORT_DETAIL)
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
     * @param Product $product
     * @return Response
     */
    public function getOne(Product $product): Response
    {
        return $this->response($product, SerializeGroup::SHORT_DETAIL);
    }

    /**
     * @Route("", methods={"POST"})
     *
     * @SWG\Post(summary="Create",
     *     @SWG\Response(
     *          response=200,
     *          description="OK",
     *          @Model(type=Product::class, groups=SerializeGroup::SHORT_DETAIL)
     *     ),
     *     @SWG\Response(
     *          response=422,
     *          description="Validation error"
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          type="string",
     *          required=true,
     *          @Model(type=ProductType::class)
     *     )
     * )
     *
     * @param Request $request
     * @param FormHandler $formHandler
     * @return Response
     */
    public function post(Request $request, FormHandler $formHandler): Response
    {
        $product = new Product;
        $formHandler->handle($request->request->all(), $product, ProductType::class);

        return $this->response($product, SerializeGroup::SHORT_DETAIL);
    }

    /**
     * @Route("/{barcode}", methods={"PUT"})
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
     *          name="barcode",
     *          in="path",
     *          type="string",
     *          required=true,
     *          description="Product barcode"
     *     ),
     *     @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          type="string",
     *          required=true,
     *          @Model(type=ProductType::class)
     *     )
     * )
     *
     * @param Request $request
     * @param FormHandler $formHandler
     * @param Product $product
     * @return Response
     */
    public function put(Request $request, FormHandler $formHandler, Product $product): Response
    {
        $formHandler->handle($request->request->all(), $product, ProductType::class);

        return $this->response();
    }

    /**
     * @Route("/{barcode}", methods={"DELETE"})
     *
     * @SWG\Delete(summary="Delete",
     *     @SWG\Response(
     *          response=200,
     *          description="OK"
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
     * @param Product $product
     * @return Response
     */
    public function delete(Product $product): Response
    {
        $this->getEm()->remove($product);
        $this->getEm()->flush();

        return $this->response();
    }
}