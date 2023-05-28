<?php

namespace App\Controller\API\User;

use App\DTO\ResponseObject;
use App\DTO\PaginateRequest;
use App\Controller\API\BaseController;
use App\Usecases\User\ListUsersUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Exception\JsonException;

class ListUsersController extends BaseController
{
    #[IsGranted('ROLE_ADMIN')] 
    #[Route('/api/user/list', name: 'api_user_lists', methods: ['GET'])]
    public function index(Request $request, ListUsersUsecase $listUsers): JsonResponse
    {

        $responseObj = new ResponseObject();
        $start = (int) $request->query->get('start', 0);
        $total = (int) $request->query->get('total', 10);

        $dto = new PaginateRequest();
        $dto->start = $start;
        $dto->total = $total;

        try {
          $responseObj = $listUsers->execute($dto);
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
