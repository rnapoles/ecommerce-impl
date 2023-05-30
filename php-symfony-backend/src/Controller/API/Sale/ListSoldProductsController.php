<?php

namespace App\Controller\API\Sale;

use App\DTO\ResponseObject;
use App\Entity\User;
use App\Controller\API\BaseController;
use App\Usecases\Sale\ListSoldProductsUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ListSoldProductsController extends BaseController
{

    #[Route('/api/sale/list-products'
      , name: 'api_sale_list_products'
      , methods: ['GET']
    )]
    public function index(
      Request $request, 
      ListSoldProductsUsecase $useCase,
      #[CurrentUser] User $user
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
