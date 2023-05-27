<?php

namespace App\Controller\API\User;

use App\DTO\RegisterUser;
use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\UserRegisterUsecase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class UserRegisterController extends BaseController
{
    #[Route('/api/user/register', name: 'api_user_register', methods: ['POST'])]
    public function index(Request $request, UserRegisterUsecase $registerUser): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {
          $registerUser->execute($request->getContent());
        } catch (\Exception $ex){

            $this->processException($ex, $responseObj);

            $jsonResponse = $this->json($responseObj);
            $jsonResponse->setStatusCode(
              JsonResponse::HTTP_BAD_REQUEST
              , $ex->getMessage()
            );

            return $jsonResponse;
        }

        return $this->json($responseObj);
    }
}
