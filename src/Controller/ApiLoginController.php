<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'app_api_login')]
    public function index(#[CurrentUser] ?User $user): JsonResponse
    {
      
        if (null === $user) {
            return $this->json([
                'message' => 'invalid credentials',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
      
        return $this->json([
            'user'  => $user->getUserIdentifier(),
            //'token' => $token,
        ]);
    }
}
