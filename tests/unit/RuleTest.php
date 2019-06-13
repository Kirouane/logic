<?php

use Logic\Facts;
use Logic\Rule;
use Logic\RuleRunner;
use Logic\Variable;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    /**
     * @test
     */
    public function ruleFather()
    {
        $father = new Facts('father', 2);

        $father->is('john', 'mike');
        $father->is('mike', 'paul');
        $father->is('mike', 'laure');
        $father->is('charles', 'jean');


        $grandfather = new Rule('grandfather', function($x, $y) use($father) {
            /** @var RuleRunner $this */
            return $this->andLogic(
                $father($x, '_Z'),
                $father('_Z', $y)
            );
        });

        self::assertSame(
            [
                [
                    '_Who' => 'paul',
                ],
                [
                    '_Who' => 'laure',
                ],
            ],
            $grandfather('john', '_Who')->toArray()
        );
    }


    /**
     * @test
     */
    public function ruleFatherWithConstantes()
    {
        $father = new Facts('father', 2);

        $father->is('john', 'mike');
        $father->is('mike', 'paul');
        $father->is('mike', 'laure');
        $father->is('charles', 'jean');


        $grandfather = new Rule('grandfather', function($x, $y) use($father) {
            /** @var RuleRunner $this */
            return $this->andLogic(
                $father($x, '_Z'),
                $father('_Z', $y)
            );
        });

        self::assertCount(1, $grandfather('john', 'paul'));
    }

    /**
     * @test
     */
    public function ruleFatherTwoVariable()
    {
        $father = new Facts('father', 2);

        $father->is('john', 'mike');
        $father->is('mike', 'paul');
        $father->is('mike', 'laure');
        $father->is('charles', 'jean');


        $grandfather = new Rule('grandfather', function($x, $y) use($father) {
            /** @var RuleRunner $this */
            return $this->andLogic(
                $father($x, '_Z'),
                $father('_Z', $y)
            );
        });

        self::assertSame(
            [
                [
                    '_Who1' => 'john',
                    '_Who2' => 'paul',
                ],
                [
                    '_Who1' => 'john',
                    '_Who2' => 'laure',
                ],
            ],
            $grandfather('_Who1', '_Who2')->toArray()
        );

    }


    /**
     * @test
     */
    public function ruleFilter()
    {
        $color = new Facts('color', 1);
        $color->is('red');
        $color->is('blue');
        $color->is('green');

        $neighboor = new Rule('neighbour', function($x, $y) use($color) {
            /** @var RuleRunner $this */
            return $this->filter(
                $this->andLogic($color($x), $color($y)),
                function($x, $y){
                    return $x !== $y;
                }
            );
        });

        self::assertSame(
            [
                [
                    '_Who' => 'red',
                ],
                [
                    '_Who' => 'blue',
                ],
            ],
            $neighboor('_Who', 'green')->toArray()
        );
    }


    /**
     * @test
     */
    public function ruleVariableFilter()
    {
        $color = new Facts('color', 1);
        $color->is('red');
        $color->is('blue');
        $color->is('green');

        $neighboor = new Rule('neighbour', function($x, $y) use($color) {
            /** @var RuleRunner $this */
            return $this->filter(
                $this->andLogic($color($x), $color($y)),
                function($x, $y){
                    return $x !== $y;
                }
            );
        });

        self::assertSame(
            [
                [
                    '_ColorA' => 'red',
                    '_ColorB' => 'blue',
                ],
                [
                    '_ColorA' => 'red',
                    '_ColorB' => 'green',
                ],
                [
                    '_ColorA' => 'blue',
                    '_ColorB' => 'red',
                ],
                [
                    '_ColorA' => 'blue',
                    '_ColorB' => 'green',
                ],
                [
                    '_ColorA' => 'green',
                    '_ColorB' => 'red',
                ],
                [
                    '_ColorA' => 'green',
                    '_ColorB' => 'blue',
                ],
            ],
            $neighboor('_ColorA', '_ColorB')->toArray()
        );
    }

    /**
     * @test
     */
    public function ruleMap()
    {
        $color = new Facts('color', 1);
        $color->is('red');
        $color->is('blue');
        $color->is('green');

        $neighboor = new Rule('neighbour', function($x, $y) use($color) {
            /** @var RuleRunner $this */
            return $this->filter(
                $this->andLogic($color($x), $color($y)),
                function($x, $y){
                    return $x !== $y;
                }
            );
        });

        $country = new Rule('country', function($x, $y, $z) use($neighboor) {
            /** @var RuleRunner $this */
            return $this->andLogic(
                $this->andLogic($neighboor($x, $y), $neighboor($y, $z)),
                $neighboor($x, $z)
            );
        });



        self::assertSame(
            [
                [
                    '_R2' => 'red',
                    '_R3' => 'green',
                ],
                [
                    '_R2' => 'green',
                    '_R3' => 'red',
                ],
            ],
            $country('blue', '_R2', '_R3')->toArray()
        );
    }

    /**
     * @test
     */
    public function orRule()
    {
        $father = new Facts('father', 2);
        $father->is('john', 'mike');
        $father->is('mike', 'paul');

        $mother = new Facts('mother', 2);
        $mother->is('lara', 'amelie');
        $mother->is('pauline', 'alice');


        $parent = new Rule('parent', function($x, $y) use($father, $mother) {
            /** @var RuleRunner $this */
            return $this->orLogic(
                $father($x, $y),
                $mother($x, $y)
            );
        });

        self::assertSame(
            [
                [
                    '_X' => 'mike'
                ],
            ],
            $parent('_X', 'paul')->toArray()
        );

        self::assertSame(
            [
                [
                    '_X' => 'alice'
                ],
            ],
            $parent('pauline', '_X')->toArray()
        );

        self::assertSame(
            [
                [
                    '_X' => 'john',
                    '_y' => 'mike',
                ],
                [
                    '_X' => 'mike',
                    '_y' => 'paul',
                ],
                [
                    '_X' => 'lara',
                    '_y' => 'amelie',
                ],
                [
                    '_X' => 'pauline',
                    '_y' => 'alice',
                ],
            ],
            $parent('_X', '_y')->toArray()
        );
    }

    /**
     * @test
     */
    public function ancestor()
    {
        $father = new Facts('father', 2);

        $father->is('achille', 'fabien');
        $father->is('fabien', 'john');
        $father->is('john', 'mike');
        $father->is('mike', 'paul');
        $father->is('mike', 'laure');
        $father->is('charles', 'jean');

        $ancestor = new Rule('ancestor', function($a, $b) use($father) {
            /** @var RuleRunner $this */
            return $this->orLogic(
                $father($a, $b),
                $this->andLogic($father($a, '_X'), $father('_X', $b)),
                $this->andLogic($father($a, '_X'), $father('_X', '_Y'), $father('_Y', $b)),
                $this->andLogic($father($a, '_X'), $father('_X', '_Y'), $father('_Y', '_Z'), $father('_Z', $b))
            );
        });

        self::assertSame(
            [
                [
                    '_R' => 'mike',
                ],
                [
                    '_R' => 'john',
                ],
                [
                    '_R' => 'fabien',
                ],
                [
                    '_R' => 'achille'
                ]
            ],
            $ancestor('_R', 'paul')->toArray()
        );
    }

    /**
     * @test
     */

    public function ancestorWithRecursion()
    {
        $father = new Facts('father', 2);

        $father->is('achille', 'fabien');
        $father->is('fabien', 'john');
        $father->is('nathan', 'john');
        $father->is('john', 'mike');
        $father->is('mike', 'paul');
        $father->is('mike', 'laure');
        $father->is('charles', 'jean');

        $ancestor = new Rule('ancestor', function($a, $b) use($father) {
            $z = new Variable();
            return $this->orLogic(
                $father($a, $b),
                $this->andLogic($father->prepare($a, $z), $this->prepare($z, $b))
            );
        });

        self::assertSame(
            [
                [
                    '_R' => 'mike',
                ],
                [
                    '_R' => 'achille',
                ],
                [
                    '_R' => 'fabien',
                ],
                [
                    '_R' => 'nathan',
                ],
                [
                    '_R' => 'john',
                ]
            ],
            $ancestor('_R', 'paul')->toArray()
        );
    }
}