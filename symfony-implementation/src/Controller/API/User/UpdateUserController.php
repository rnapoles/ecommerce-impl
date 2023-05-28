<?php

namespace App\Controller\API\User;

use App\DTO\ResponseObject;
use App\Controller\API\BaseController;
use App\Usecases\User\UpdateUserUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UpdateUserController extends BaseController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route(
      '/api/user/update/{id}',
      name: 'api_user_update',
      methods: ['POST'],
      requirements: ['id' => '\d+']
    )]
    public function index(
      Request $request,
      UpdateUserUsecase $updateUser,
    ): JsonResponse
    {
      
        $responseObj = new ResponseObject();

        try {

          $result = $updateUser->execute($request->getContent());
          if(!$result){
            throw $this->createNotFoundException(
                'User not found'
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


        return $this->json($responseObj);
    }
}
