<?php
namespace ngyuki\Sandbox;

use Interop\Container\Exception\ContainerException as ContainerExceptionInterface;

class ContainerException extends \LogicException implements ContainerExceptionInterface
{
}
