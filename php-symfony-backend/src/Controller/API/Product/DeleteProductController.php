<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\Product\DeleteProductUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class DeleteProductController extends BaseController
{
    #[Security("is_granted('ROLE_ADMIN') OR is_granted('ROLE_EDITOR')")]
    #[Route(
      '/api/product/delete/{id}',
      name: 'api_product_delete',
      methods: ['DELETE'],
      requirements: ['id' => '\d+']
    )]
    public function index(int $id, DeleteProductUsecase $useCase): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {
          $useCase->execute($id);
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
