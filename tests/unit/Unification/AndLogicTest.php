<?php


use Logic\Constant;
use Logic\Solution;
use Logic\Solutions;
use Logic\Unification\AndLogic;
use Logic\Variable;
use PHPUnit\Framework\TestCase;

class AndLogicTest extends TestCase
{
    public function AndLogicProvider()
    {
        return [
            [
                new Solutions([
                    new Solution([
                        'Z' => new Variable('Z', 'mike')
                    ])
                ]),
                new Solutions([
                    new Solution([
                        'Z' => new Variable('Z', 'john'),
                        'Who' => new Variable('Who', 'mike')
                    ]),
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Who' => new Variable('Who', 'paul')
                    ]),
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Who' => new Variable('Who', 'laure')
                    ]),

                    new Solution([
                        'Z' => new Variable('Z', 'charles'),
                        'Who' => new Variable('Who', 'jean')
                    ])
                ]),
                [
                    [
                        'Z' => 'mike',
                        'Who' => 'paul',
                    ],
                    [
                        'Z' => 'mike',
                        'Who' => 'laure',
                    ],
                ],
            ],
            [
                new Solutions([
                    new Solution([
                        'Who1' => new Variable('Who1', 'john'),
                        'Z' => new Variable('Z', 'mike')
                    ]),
                    new Solution([
                        'Who1' => new Variable('Who1', 'mike'),
                        'Z' => new Variable('Z', 'paul')
                    ]),
                    new Solution([
                        'Who1' => new Variable('Who1', 'mike'),
                        'Z' => new Variable('Z', 'laure')
                    ]),
                    new Solution([
                        'Who1' => new Variable('Who1', 'charles'),
                        'Z' => new Variable('Z', 'jean')
                    ])
                ]),
                new Solutions([
                    new Solution([
                        'Z' => new Variable('Z', 'john'),
                        'Who2' => new Variable('Who2', 'mike')
                    ]),
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Who2' => new Variable('Who2', 'paul')
                    ]),
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Who2' => new Variable('Who2', 'laure')
                    ]),

                    new Solution([
                        'Z' => new Variable('Z', 'charles'),
                        'Who2' => new Variable('Who2', 'jean')
                    ])
                ]),
                [
                    [
                        'Z' => 'mike',
                        'Who1' => 'john',
                        'Who2' => 'paul',
                    ],
                    [
                        'Z' => 'mike',
                        'Who1' => 'john',
                        'Who2' => 'laure',
                    ],
                ],
            ],
            [
                new Solutions([
                    new Solution([
                        'Const1' => new Constant('john', 'Const1'),
                        'Z' => new Variable('Z', 'mike')
                    ])
                ]),
                new Solutions([
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Const2' => new Constant('paul', 'Const2'),
                    ])
                ]),
                [
                    [
                        'Const1' => 'john',
                        'Z' => 'mike',
                        'Const2' => 'paul',
                    ]
                ],
            ],
            [
                new Solutions([
                    new Solution([
                        'Const1' => new Constant('john', 'Const1'),
                        'Z' => new Variable('Z', 'mike')
                    ])
                ]),
                new Solutions([
                    new Solution([
                        'Z' => new Variable('Z', 'john'),
                        'Who' => new Variable('Who', 'mike')
                    ]),
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Who' => new Variable('Who', 'paul')
                    ]),
                    new Solution([
                        'Z' => new Variable('Z', 'mike'),
                        'Who' => new Variable('Who', 'laure')
                    ]),

                    new Solution([
                        'Z' => new Variable('Z', 'charles'),
                        'Who' => new Variable('Who', 'jean')
                    ])
                ]),
                [
                    [
                        'Const1' => 'john',
                        'Z' => 'mike',
                        'Who' => 'paul',
                    ],
                    [
                        'Const1' => 'john',
                        'Z' => 'mike',
                        'Who' => 'laure',
                    ],
                ],
            ],
            [
                new Solutions([
                    new Solution([
                        'Z' => new Variable('Who', 'red'),
                    ]),
                    new Solution([
                        'Z' => new Variable('Who', 'blue')
                    ]),
                    new Solution([
                        'Z' => new Variable('Who', 'green')
                    ])
                ]),
                new Solutions([
                    new Solution([
                        'Z' => new Constant('green', 'Const1'),
                    ])
                ]),
                [
                    [
                        'Who' => 'red',
                        'Const1' => 'green'
                    ],
                    [
                        'Who' => 'blue',
                        'Const1' => 'green'
                    ],
                    [
                        'Who' => 'green',
                        'Const1' => 'green'
                    ]
                ],
            ]
        ];
    }

    /**
     * @test
     * @dataProvider AndLogicProvider
     */
    public function andLogic($optionA, $optionB, $expected)
    {
        $unification = new AndLogic();
        $result = $unification->unify($optionA, $optionB);
        $this->assertArraySimilar($expected, $result->expand());

        $result = $unification->unify($optionB, $optionA);
        $this->assertArraySimilar($expected, $result->expand());

    }

    protected function assertArraySimilar(array $expected, array $array)
    {
        $this->assertTrue(count(array_diff_key($array, $expected)) === 0);
        foreach ($expected as $key => $value) {
            if (is_array($value)) {
                $this->assertArraySimilar($value, $array[$key]);
            } else {
                $this->assertContains($value, $array);
            }
        }
    }
}
