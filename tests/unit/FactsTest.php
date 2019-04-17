<?php
use Logic\Facts;
use Logic\Query;
use Logic\Variable;
use Logic\VariableCombination;
use PHPUnit\Framework\TestCase;

class FactsTest extends TestCase
{
    public function queryOneArgementWithConstantProvider()
    {
        return [
            [['red'], true],
            [['yellow'], false],
            [['green'], true]
        ];
    }

    /**
     * @test
     * @dataProvider queryOneArgementWithConstantProvider
     */
    public function queryOneArgementWithConstant($query, $expected)
    {
        $color = new Facts('color', 1);

        $color->is('blue');
        $color->is('red');
        $color->is('green');

        self::assertSame($expected, $color(...$query)->count() > 0);
    }

    public function queryTwoArgementWithConstantProvider()
    {
        return [
            [['pierre', 'paul'], true],
            [['pierre', 'mike'], false],
            [['john', 'joe'], false]
        ];
    }


    /**
     * @test
     * @dataProvider queryTwoArgementWithConstantProvider
     */
    public function queryTwoArgementWithConstant($query, $expected)
    {
        $father = new Facts('father', 2);

        $father->is('alice', 'mike');
        $father->is('pierre', 'paul');
        $father->is('robin', 'laure');

        self::assertSame($expected, $father(...$query)->count() > 0);
    }


    public function arityProvider()
    {
        return [
            [1, ['argment1'], false],
            [1, ['argment1', 'argment2'], true],
            [2, ['argment1', 'argment2'], false],
            [2, ['argment1'], true],
        ];
    }

    /**
     * @test
     * @dataProvider arityProvider
     */
    public function arity($arity, $arguments, $expectedException)
    {
        if ($expectedException) {
            $this->expectException(\Exception::class);
        }
        $father = new Facts('father', $arity);
        self::assertNull($father->is(...$arguments));
    }

    /**
     * @test
     */
    public function queryOneArgementWithVariable()
    {
        $color = new Facts('color', 1);

        $color->is('blue');
        $color->is('red');
        $color->is('green');

        self::assertSame(true, count($color('_X')) > 0);
        self::assertSame(
            [
                ['_X' => 'blue'],
                ['_X' => 'red'],
                ['_X' => 'green']
            ],
            $color('_X')->toArray()
        );
    }


    /**
     * @test
     */
    public function queryTwoArgementWithVariable()
    {
        $father = new Facts('father', 2);

        $father->is('john', 'mike');
        $father->is('john', 'paul');
        $father->is('robin', 'laure');

        self::assertSame(true, $father('john', '_X')->count() > 0);
        self::assertSame(
            [
                ['_X' => 'mike'],
                ['_X' => 'paul']
            ],
            $father('john', '_X')->toArray()
        );
    }


    /**
     * @test
     */
    public function queryTwoArgementWithTwoVariable()
    {
        $father = new Facts('father', 2);

        $father->is('john', 'mike');
        $father->is('john', 'paul');
        $father->is('robin', 'laure');

        self::assertSame(
            [
                ['_X' => 'john', '_Y' => 'mike'],
                ['_X' => 'john', '_Y' => 'paul'],
                ['_X' => 'robin', '_Y' => 'laure']
            ],
            $father('_X', '_Y')->toArray()
        );
    }

}