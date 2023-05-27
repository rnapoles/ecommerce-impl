<?php

namespace App\Controller\API;

use \Throwable;
use \Exception;
use App\DTO\ResponseObject;
use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{

  protected string $projectDir = '';

  public function __construct(
    protected readonly LoggerInterface $logger,
    protected readonly KernelInterface $kernel,
    protected readonly ValidatorInterface $validator,
  ) 
  {
    $this->projectDir = $kernel->getProjectDir();
  }

  public function processException(\Throwable $ex, ResponseObject $responseObj) 
  {

    $msg = $ex->getMessage();
    $responseObj->message = $msg;
    $responseObj->success = false;

    if($ex instanceof ValidationException){
      $responseObj->errors = $ex->errors;
    }
    
    //dump($ex);
    //todo: custom log exception

  }
}
