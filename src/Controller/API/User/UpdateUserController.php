<?php

namespace App\Controller\API\User;

use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\UpdateUserUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class UpdateUserController extends BaseController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route(
      '/api/user/update/{id}',
      name: 'api_user_register',
      methods: ['POST'],
      requirements: ['id' => '\d+']
    )]
    public function index(
      Request $request,
      #[MapEntity] User $user,
      UpdateUserUsecase $updateUser
    ): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {
          $updateUser->execute($request->getContent());
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
