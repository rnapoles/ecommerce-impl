<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\DTO\Product\CreateProduct;

class ApiInfoController extends AbstractController
{
    #[Route('/api/info', name: 'app_api_info')]
    public function index(): JsonResponse
    {
      
        $dto = new CreateProduct();
        
        return $this->json($dto);
    }
}
