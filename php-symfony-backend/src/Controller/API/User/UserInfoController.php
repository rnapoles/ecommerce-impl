<?php

namespace App\Controller\API\User;

use App\DTO\ResponseObject;
use App\Entity\User;
use App\Controller\API\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserInfoController extends BaseController
{
    #[Route('/api/user', name: 'api_user_info', methods: ['GET'])]
    public function index(#[CurrentUser] User $user): JsonResponse
    {
        
        $responseObj = new ResponseObject();

        $responseObj->payload = $user;

        return $this->json($responseObj);
    }
}
