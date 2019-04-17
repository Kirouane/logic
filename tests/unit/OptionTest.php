<?php


use Logic\Constant;
use Logic\Option;
use Logic\Variable;

class OptionTest extends \PHPUnit\Framework\TestCase
{


    public function matchProvider()
    {
        return [
            [
                new Option([
                    new Variable('Who1', 'john'),
                    new Variable('Z', 'mike')
                ]),
                new Option([
                    new Variable('Z', 'john'),
                    new Variable('Who2', 'mike')
                ]),
                []
            ],
            [
                new Option([
                    new Variable('Who1', 'john'),
                    new Variable('Z', 'mike')
                ]),
                new Option([
                    new Variable('Z', 'mike'),
                    new Variable('Who2', 'paul')
                ]),
                [
                    'Who1' => 'john',
                    'Z' => 'mike',
                    'Who2' => 'paul'
                ]
            ],
            [

                new Option([
                    new Variable('Z', 'mike')
                ]),
                new Option([
                    new Variable('Z', 'john'),
                    new Variable('Who', 'mike')
                ]),
                []
            ],
            [

                new Option([
                    new Variable('Z', 'mike')
                ]),
                new Option([
                    new Variable('Z', 'mike'),
                    new Variable('Who', 'paul')
                ]),
                [
                    'Z' => 'mike',
                    'Who' => 'paul'
                ]
            ],
            [

                new Option([
                    new Variable('Who', 'red')
                ]),
                new Option([
                    new Constant('red', '_123'),
                ]),
                [
                    'Who' => 'red'
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider matchProvider
     */
    public function match($optionA, $optionB, $expected)
    {
        self::assertSame($expected, $optionA->match($optionB)->toArray());
    }
}