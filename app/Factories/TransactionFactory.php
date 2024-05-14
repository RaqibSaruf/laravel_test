<?php

declare(strict_types=1);

namespace App\Factories;

use Illuminate\Http\Response as Res;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class TransactionFactory
{
  public static function createInstance(string $service)
  {
    $class = "\App\Factories\Transaction\\" . ucfirst($service);
    if (class_exists($class)) {
      return new $class();
    } else {
      throw new ServiceUnavailableHttpException(null, 'Service is not available', null, Res::HTTP_SERVICE_UNAVAILABLE);
    }
  }
}