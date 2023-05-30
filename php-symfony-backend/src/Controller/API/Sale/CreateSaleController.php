<?php

namespace App\Controller\API\Sale;

use App\DTO\ResponseObject;
use App\Entity\User;
use App\Controller\API\BaseController;
use App\Usecases\Sale\CreateSaleUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class CreateSaleController extends BaseController
{

    #[Route('/api/sale/create', name: 'api_sale_create', methods: ['POST'])]
    public function index(
      Request $request, 
      CreateSaleUsecase $useCase,
      #[CurrentUser] User $user
    ): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {
          $userId = $user->getId();
          $responseObj->payload = $useCase->execute($request->getContent(), $userId);
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
