<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\Product\ReadProductUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class ReadProductController extends BaseController
{
    #[Route(
      '/api/product/{sku}',
      name: 'api_product_read',
      methods: ['GET'],
      requirements: ['sku' => '(\w+-)+\w+']
    )]
    public function index(string $sku, ReadProductUsecase $useCase): JsonResponse
    {

        $responseObj = new ResponseObject();

        try {
          $result = $useCase->execute($sku);

          if(!$result){
            throw $this->createNotFoundException(
                'Product not found'
            );
          }
          
          $responseObj->payload = $result;
        } catch (\Exception $ex){

            $this->processException($ex, $responseObj);

            $statusCode = JsonResponse::HTTP_BAD_REQUEST;
            if($ex instanceof HttpException){
              $statusCode = $ex->getStatusCode();
            }

            $jsonResponse = $this->json($responseObj);
            $jsonResponse->setStatusCode(
              $statusCode,
              $ex->getMessage()
            );

            return $jsonResponse;
        }

        return $this->json($responseObj, JsonResponse::HTTP_OK);
    }
}
