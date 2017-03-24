<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;
use ngyuki\Sandbox\Composite;
use ngyuki\Sandbox\Container;
use ngyuki\Sandbox\ContainerException;

class Test extends TestCase
{
    function test()
    {
        {
            $container = new Container(
                [
                    'a' => function (ContainerInterface $c) {
                        return $c->get('a');
                    },
                ]
            );

            try {
                $container->get('a');
                assert(false);
            } catch (ContainerException $ex) {
                // ok
            }
        }
        {
            $container = new Container(
                [
                    'a' => function (ContainerInterface $c) {
                        return $c->get('b');
                    },
                    'b' => function (ContainerInterface $c) {
                        return $c->get('a');
                    },
                ]
            );
            try {
                $container->get('a');
                assert(false);
            } catch (ContainerException $ex) {
                // ok
            }
        }
        {
            $container = new Container(
                [
                    'a' => function (ContainerInterface $c) {
                        return "AAA" . $c->get('b');
                    },
                    'b' => "BBB",
                ]
            );

            assert($container->get('a') === "AAABBB");
        }
        {
            $container = new Container(
                [
                    'obj' => function () {
                        return new \stdClass();
                    },
                ]
            );

            assert($container->get('obj') === $container->get('obj'));
        }
        {
            $composite = new Composite();
            $composite->add(
                new Container(
                    [
                        'a' => function (ContainerInterface $c) {
                            return "A" . $c->get('b');
                        },
                        'c' => "C",
                    ],
                    $composite
                )
            );
            $composite->add(
                new Container(
                    [
                        'b' => function (ContainerInterface $c) {
                            return "B" . $c->get('c');
                        },
                        'c' => "c",
                    ],
                    $composite
                )
            );

            assert($composite->get('a') === "ABC");
            assert($composite->get('b') === "BC");
            assert($composite->get('c') === "C");
        }
        {
            $composite = new Composite();
            $composite->add(
                new Container(
                    [
                        'a' => function (ContainerInterface $c) {
                            return "A" . $c->get('b');
                        },
                    ],
                    $composite
                )
            );
            $composite->add(
                new Container(
                    [
                        'b' => function (ContainerInterface $c) {
                            return "B" . $c->get('a');
                        },
                    ],
                    $composite
                )
            );
            try {
                $composite->get('a');
                assert(false);
            } catch (ContainerException $ex) {
                // ok
            }
        }

        $this->assertTrue(true);
    }
}



