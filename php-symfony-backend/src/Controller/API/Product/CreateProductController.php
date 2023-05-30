<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\Product\CreateProductUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class CreateProductController extends BaseController
{
    #[Security("is_granted('ROLE_ADMIN') OR is_granted('ROLE_EDITOR')")]
    #[Route('/api/product/create', name: 'api_product_create', methods: ['POST'])]
    public function index(Request $request, CreateProductUsecase $useCase): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {
          $responseObj->payload = $useCase->execute($request->getContent());
        } catch (\Exception $ex){

            $this->processException($ex, $responseObj);

            $jsonResponse = $this->json($responseObj);
            $jsonResponse->setStatusCode(
              JsonResponse::HTTP_BAD_REQUEST
              , $ex->getMessage()
            );

            return $jsonResponse;
        }

        return $this->json($responseObj, JsonResponse::HTTP_CREATED);
    }
}
