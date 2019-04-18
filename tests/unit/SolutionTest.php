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
                    'Who1' => new Variable('Who1', 'john'),
                    'Z' => new Variable('Z', 'mike')
                ]),
                new Solution([
                    'Z' =>  new Variable('Z', 'john'),
                    'Who2' => new Variable('Who2', 'mike')
                ]),
                []
            ],
            [
                new Solution([
                    'Who1' => new Variable('Who1', 'john'),
                    'Z' => new Variable('Z', 'mike')
                ]),
                new Solution([
                    'Z' => new Variable('Z', 'mike'),
                    'Who2' => new Variable('Who2', 'paul')
                ]),
                [
                    'Who1' => 'john',
                    'Z' => 'mike',
                    'Who2' => 'paul'
                ]
            ],
            [

                new Solution([
                    'Z' => new Variable('Z', 'mike')
                ]),
                new Solution([
                    'Z' => new Variable('Z', 'john'),
                    'Who' =>  new Variable('Who', 'mike')
                ]),
                []
            ],
            [

                new Solution([
                    'Z' => new Variable('Z', 'mike')
                ]),
                new Solution([
                    'Z' => new Variable('Z', 'mike'),
                    'Who' => new Variable('Who', 'paul')
                ]),
                [
                    'Z' => 'mike',
                    'Who' => 'paul'
                ]
            ],
            [

                new Solution([
                    'Who' => new Variable('Who', 'red')
                ]),
                new Solution([
                    '_123' => new Constant('red', '_123'),
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