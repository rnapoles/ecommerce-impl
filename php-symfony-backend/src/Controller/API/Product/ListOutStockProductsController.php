<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\Entity\User;
use App\Controller\API\BaseController;
use App\Usecases\Product\ListOutStockProductsUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ListOutStockProductsController extends BaseController
{

    #[Security("is_granted('ROLE_ADMIN') OR is_granted('ROLE_EDITOR')")]
    #[Route('/api/product/out-stock'
      , name: 'api_product_out'
      , methods: ['GET']
    )]
    public function index(
      Request $request, 
      ListOutStockProductsUsecase $useCase,
    ): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {
          $responseObj->payload = $useCase->execute();
        } catch (\Exception $ex){

            $this->processException($ex, $responseObj);

            $jsonResponse = $this->json($responseObj);
            $jsonResponse->setStatusCode(
              JsonResponse::HTTP_BAD_REQUEST
              , $ex->getMessage()
            );

            return $jsonResponse;
        }

        return $this->json($responseObj, JsonResponse::HTTP_OK);
    }
}
