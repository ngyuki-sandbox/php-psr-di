<?php
namespace ngyuki\Sandbox;

use Interop\Container\ContainerInterface;

/**
 * シンプルなコンポジットコンテナ
 */
class Composite implements ContainerInterface
{
    /**
     * @var ContainerInterface[]
     */
    private $containers = [];

    public function add(ContainerInterface $container)
    {
        $this->containers[] = $container;
        return $this;
    }

    public function get($id)
    {
        foreach ($this->containers as $container) {
            if ($container->has($id)) {
                return $container->get($id);
            }
        }
        throw new NotFoundException("$id NotFound");
    }

    public function has($id)
    {
        foreach ($this->containers as $container) {
            if ($container->has($id)) {
                return true;
            }
        }
        return false;
    }
}
