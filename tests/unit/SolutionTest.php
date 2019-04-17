<?php


use Logic\Constant;
use Logic\Solution;
use Logic\Variable;

class SolutionTest extends \PHPUnit\Framework\TestCase
{


    public function matchProvider()
    {
        return [
            [
                new Solution([
                    new Variable('Who1', 'john'),
                    new Variable('Z', 'mike')
                ]),
                new Solution([
                    new Variable('Z', 'john'),
                    new Variable('Who2', 'mike')
                ]),
                []
            ],
            [
                new Solution([
                    new Variable('Who1', 'john'),
                    new Variable('Z', 'mike')
                ]),
                new Solution([
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

                new Solution([
                    new Variable('Z', 'mike')
                ]),
                new Solution([
                    new Variable('Z', 'john'),
                    new Variable('Who', 'mike')
                ]),
                []
            ],
            [

                new Solution([
                    new Variable('Z', 'mike')
                ]),
                new Solution([
                    new Variable('Z', 'mike'),
                    new Variable('Who', 'paul')
                ]),
                [
                    'Z' => 'mike',
                    'Who' => 'paul'
                ]
            ],
            [

                new Solution([
                    new Variable('Who', 'red')
                ]),
                new Solution([
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