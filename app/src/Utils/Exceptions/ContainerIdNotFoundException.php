<?php

declare(strict_types=1);

namespace App\Utils\Exceptions;


use Exception;
use Psr\Container\NotFoundExceptionInterface;

class ContainerIdNotFoundException extends Exception implements NotFoundExceptionInterface
{

}