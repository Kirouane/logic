<?php

use Logic\Facts;
use Logic\Rule;
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
            /** @var \Logic\RuleRunner $this */
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
            /** @var \Logic\RuleRunner $this */
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
            /** @var \Logic\RuleRunner $this */
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
            /** @var \Logic\RuleRunner $this */
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
            /** @var \Logic\RuleRunner $this */
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
            /** @var \Logic\RuleRunner $this */
            return $this->filter(
                $this->andLogic($color($x), $color($y)),
                function($x, $y){
                    return $x !== $y;
                }
            );
        });

        $country = new Rule('country', function($x, $y, $z) use($neighboor) {
            /** @var \Logic\RuleRunner $this */
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
}