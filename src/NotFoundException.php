<?php
namespace ngyuki\Sandbox;

use Interop\Container\Exception\NotFoundException as NotFoundExceptionInterface;

class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
