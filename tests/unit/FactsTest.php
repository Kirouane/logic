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
            [['red'], true, []],
            [['yellow'], false, []],
            [['green'], true, []]
        ];
    }

    /**
     * @test
     * @dataProvider queryOneArgementWithConstantProvider
     */
    public function queryOneArgementWithConstant($query, $expected, $expectedResult)
    {
        $color = new Facts('color', 1);

        $color->is(['blue']);
        $color->is(['red']);
        $color->is(['green']);

        $query = new Query($query);
        self::assertSame($expected, $color($query));
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

        $father->is(['alice', 'mike']);
        $father->is(['pierre', 'paul']);
        $father->is(['robin', 'laure']);

        self::assertSame($expected, $father(new Query($query)));
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
        self::assertNull($father->is($arguments));
    }

    /**
     * @test
     */
    public function queryOneArgementWithVariable()
    {
        $color = new Facts('color', 1);

        $color->is(['blue']);
        $color->is(['red']);
        $color->is(['green']);

        $query = new Query([new Variable('X')]);
        self::assertSame(true, $color($query));
        self::assertSame(
            [
                ['X' => 'blue'],
                ['X' => 'red'],
                ['X' => 'green']
            ],
            $query->getOptions()->toArray()
        );
    }


    /**
     * @test
     */
    public function queryTwoArgementWithVariable()
    {
        $father = new Facts('father', 2);

        $father->is(['john', 'mike']);
        $father->is(['john', 'paul']);
        $father->is(['robin', 'laure']);

        $query = new Query(['john', new Variable('X')]);
        self::assertSame(true, $father($query));
        self::assertSame(
            [
                ['X' => 'mike'],
                ['X' => 'paul']
            ],
            $query->getOptions()->toArray()
        );
    }


    /**
     * @test
     */
    public function queryTwoArgementWithTwoVariable()
    {
        $father = new Facts('father', 2);

        $father->is(['john', 'mike']);
        $father->is(['john', 'paul']);
        $father->is(['robin', 'laure']);

        $query = new Query([new Variable('X'), new Variable('Y')]);
        self::assertSame(true, $father($query));
        self::assertSame(
            [
                ['X' => 'john', 'Y' => 'mike'],
                ['X' => 'john', 'Y' => 'paul'],
                ['X' => 'robin', 'Y' => 'laure']
            ],
            $query->getOptions()->toArray()
        );
    }

}