<?php
namespace ngyuki\Sandbox;

use Interop\Container\ContainerInterface;

/**
 * デリゲートコンテナに対応したコンテナ
 */
class Container implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $delegate;

    /**
     * @var array
     */
    private $factories = [];

    /**
     * @var array
     */
    private $founds = [];

    /**
     * @var array
     */
    private $values = [];

    public function __construct(array $factories, ContainerInterface $delegate = null)
    {
        $this->factories = $factories;

        // 親コンテナが指定されていればそれを、なければ自分を
        $this->delegate = $delegate ?: $this;
    }

    public function get($id)
    {
        if (array_key_exists($id, $this->values)) {
            // 自分で持っているならそのまま帰す
            return $this->values[$id];
        }
        if (array_key_exists($id, $this->founds)) {
            // 発見済のキーが再要求されたなら循環参照している
            throw new ContainerException("[$id] Cyclic reference");
        }
        if (array_key_exists($id, $this->factories)) {
            // ファクトリを呼び出してインスタンス作成
            $this->founds[$id] = true;
            $factory = $this->factories[$id];

            if ($factory instanceof \Closure || is_object($factory) && is_callable($factory)) {
                // クロージャー、または、__invoke を実装したオブジェクトの場合
                // デリゲートコンテナを引数に渡す
                $value = $factory($this->delegate);
            } else {
                $value = $factory;
            }
            $this->values[$id] = $value;
            return $value;
        }
        throw new NotFoundException("[$id] NotFound");
    }

    public function has($id)
    {
        return array_key_exists($id, $this->factories) || array_key_exists($id, $this->values);
    }
}
