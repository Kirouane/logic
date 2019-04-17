<?php


use Logic\Constant;
use Logic\Option;
use Logic\Options;
use Logic\Unification\AndLogic;
use Logic\Variable;
use PHPUnit\Framework\TestCase;

class AndLogicTest extends TestCase
{
    public function AndLogicProvider()
    {
        return [
            [
                new Options([
                    new Option([
                        new Variable('Z', 'mike')
                    ])
                ]),
                new Options([
                    new Option([
                        new Variable('Z', 'john'),
                        new Variable('Who', 'mike')
                    ]),
                    new Option([
                        new Variable('Z', 'mike'),
                        new Variable('Who', 'paul')
                    ]),
                    new Option([
                        new Variable('Z', 'mike'),
                        new Variable('Who', 'laure')
                    ]),

                    new Option([
                        new Variable('Z', 'charles'),
                        new Variable('Who', 'jean')
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
                new Options([
                    new Option([
                        new Variable('Who1', 'john'),
                        new Variable('Z', 'mike')
                    ]),
                    new Option([
                        new Variable('Who1', 'mike'),
                        new Variable('Z', 'paul')
                    ]),
                    new Option([
                        new Variable('Who1', 'mike'),
                        new Variable('Z', 'laure')
                    ]),
                    new Option([
                        new Variable('Who1', 'charles'),
                        new Variable('Z', 'jean')
                    ])
                ]),
                new Options([
                    new Option([
                        new Variable('Z', 'john'),
                        new Variable('Who2', 'mike')
                    ]),
                    new Option([
                        new Variable('Z', 'mike'),
                        new Variable('Who2', 'paul')
                    ]),
                    new Option([
                        new Variable('Z', 'mike'),
                        new Variable('Who2', 'laure')
                    ]),

                    new Option([
                        new Variable('Z', 'charles'),
                        new Variable('Who2', 'jean')
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
                new Options([
                    new Option([
                        new Constant('john', 'Const1'),
                        new Variable('Z', 'mike')
                    ])
                ]),
                new Options([
                    new Option([
                        new Variable('Z', 'mike'),
                        new Constant('paul', 'Const2'),
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
                new Options([
                    new Option([
                        new Constant('john', 'Const1'),
                        new Variable('Z', 'mike')
                    ])
                ]),
                new Options([
                    new Option([
                        new Variable('Z', 'john'),
                        new Variable('Who', 'mike')
                    ]),
                    new Option([
                        new Variable('Z', 'mike'),
                        new Variable('Who', 'paul')
                    ]),
                    new Option([
                        new Variable('Z', 'mike'),
                        new Variable('Who', 'laure')
                    ]),

                    new Option([
                        new Variable('Z', 'charles'),
                        new Variable('Who', 'jean')
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
                new Options([
                    new Option([
                        new Variable('Who', 'red'),
                    ]),
                    new Option([
                        new Variable('Who', 'blue')
                    ]),
                    new Option([
                        new Variable('Who', 'green')
                    ])
                ]),
                new Options([
                    new Option([
                        new Constant('green', 'Const1'),
                    ])
                ]),
                [
                    [
                        'Who' => 'red'
                    ],
                    [
                        'Who' => 'blue'
                    ],
                    [
                        'Who' => 'green'
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