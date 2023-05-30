<?php

namespace App\Usecases;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Exception\ValidationException;

abstract class BaseUsecase implements IUsecase {

  function __construct(
    private ValidatorInterface $validator,
  ){
    
  }

  public function validate(object $obj){

    $errors = $this->validator->validate($obj);
    if (count($errors) > 0) {
        
        $list = [];
        foreach ($errors as $err) {
          
          $list[] = [
            'message' => $err->getMessage(),
            'invalidValue' => $err->getInvalidValue(),
            'propertyPath' => $err->getPropertyPath(),
          ];

        }
        
        throw new ValidationException($list);
    }

  }

}