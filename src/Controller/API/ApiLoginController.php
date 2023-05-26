<?php

namespace App\Controller\API;

use App\DTO\ResponseObject;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends BaseController
{
    #[Route('/api/login', name: 'app_api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
        
        $responseObj = new ResponseObject();
        
        if (null === $user) {
            $responseObj->message = 'invalid credentials';
            return $this->json($responseObj, JsonResponse::HTTP_UNAUTHORIZED);
        }

        $responseObj->payload = [
            'user'  => $user->getUserIdentifier(),
            //'token' => $token,
        ];

        return $this->json($responseObj);
    }
}
