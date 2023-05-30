<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\DTO\Product\ResponseSearchTotal;
use App\DTO\PaginateRequest;
use App\Controller\API\BaseController;
use App\Usecases\Product\SearchProductsUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class SearchProductsTotalController extends BaseController
{

    #[Route('/api/product/search-total',
      name: 'api_product_search_total',
      priority: 1,
      methods: ['GET']
    )]
    public function index(Request $request, SearchProductsUsecase $useCase): JsonResponse
    {
      
        $responseObj = new ResponseObject();
        $query = $request->query->get('query', '');

        $dto = new PaginateRequest();
        $dto->start = 0;
        $dto->total = 10;
        $dto->query = $query;

        try {
          $result = $useCase->execute($dto);
          $payload = new ResponseSearchTotal();
          $payload->total = $result->total;
          $responseObj->payload = $payload;
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
