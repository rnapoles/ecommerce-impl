<?php

namespace App\Controller\API\Product;

use App\DTO\ResponseObject;
use App\DTO\PaginateRequest;
use App\Controller\API\BaseController;
use App\Usecases\Product\SearchProductsUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class SearchProductController extends BaseController
{

    #[Route('/api/product/search', 
      name: 'api_product_search',
      priority: 1,
      methods: ['GET']
    )]
    public function index(Request $request, SearchProductsUsecase $useCase): JsonResponse
    {
      
        $responseObj = new ResponseObject();
        $start = (int) $request->query->get('start', 0);
        $total = (int) $request->query->get('total', 10);
        $query = $request->query->get('query', 10);

        $dto = new PaginateRequest();
        $dto->start = $start;
        $dto->total = $total;
        $dto->query = $query;

        try {
          $responseObj = $useCase->execute($dto);
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
