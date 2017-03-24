# PSR-11 Container interface のサンプル実装

- https://github.com/container-interop/fig-standards/blob/master/proposed/container.md

## デリゲートコンテナ

- 外側のコンテナはただのシンプルなコンポジットコンテナで良さそう
- 内側のコンテナは親となるデリゲートコンテナを知っている必要がある
- この実装の場合、インスタンスはファクトリを呼び出して生成しているので・・
    - 引数として与えるコンテナのインスタンスにデリゲートコンテナを渡す
