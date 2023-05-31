<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{

  #[Route(path: '/', name: 'index_0')]
  public function indexAction() : Response
  {
      return $this->render('index/index.html.twig', []);
  }
}
