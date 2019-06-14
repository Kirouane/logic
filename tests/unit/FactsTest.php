<?php
use Logic\Facts;
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
        $color = new Facts();

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
        $father = new Facts();

        $father->is('alice', 'mike');
        $father->is('pierre', 'paul');
        $father->is('robin', 'laure');

        self::assertSame($expected, $father(...$query)->count() > 0);
    }


    /**
     * @test
     */
    public function queryOneArgementWithVariable()
    {
        $color = new Facts();

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
        $father = new Facts();

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
        $father = new Facts();

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

    /**
     * @test
     */
    public function queryTwoArgementWithSameTwoVariable()
    {
        $father = new Facts();

        $father->is('john', 'mike');
        $father->is('john', 'paul');
        $father->is('robin', 'robin');

        self::assertSame(
            [
                ['_X' => 'robin']
            ],
            $father('_X', '_X')->toArray()
        );
    }

}