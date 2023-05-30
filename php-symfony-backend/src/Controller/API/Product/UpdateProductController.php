<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\Product\UpdateProductUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class UpdateProductController extends BaseController
{
    #[Security("is_granted('ROLE_ADMIN') OR is_granted('ROLE_EDITOR')")]
    #[Route(
      '/api/product/update/{id}',
      name: 'api_product_update',
      methods: ['PATCH'],
      requirements: ['id' => '\d+']
    )]
    public function index(Request $request, UpdateProductUsecase $useCase): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {

          $result = $useCase->execute($request->getContent());

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
