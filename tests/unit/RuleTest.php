<?php

use Logic\Facts;
use Logic\Query;
use Logic\Rule;
use Logic\Unification\AndLogic;
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

        $father->is(['john', 'mike']);
        $father->is(['mike', 'paul']);
        $father->is(['mike', 'laure']);
        $father->is(['charles', 'jean']);


        $grandfather = new Rule('grandfather', function(Query $query) use($father) {
            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0], new Variable('Z')]))->apply($father),
                (new Query([new Variable('Z'), $query[1]]))->apply($father)
            );

            $query->setOptions($options);

            return $options->count() > 0;
        });

        $query = new Query(['john', new Variable('Who')]);
        self::assertSame(true, $grandfather($query));

        self::assertSame(
            [
                [
                    'Who' => 'paul',
                ],
                [
                    'Who' => 'laure',
                ],
            ],
            $query->getOptions()->toArray()
        );
    }


    /**
     * @test
     */
    public function ruleFatherWithConstantes()
    {
        $father = new Facts('father', 2);

        $father->is(['john', 'mike']);
        $father->is(['mike', 'paul']);
        $father->is(['mike', 'laure']);
        $father->is(['charles', 'jean']);


        $grandfather = new Rule('grandfather', function(Query $query) use($father) {
            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0], new Variable('Z')]))->apply($father),
                (new Query([new Variable('Z'), $query[1]]))->apply($father)
            );

            $query->setOptions($options);

            return $options->count() > 0;
        });

        $query = new Query(['john', 'paul']);
        self::assertSame(true, $grandfather($query));
        self::assertCount(1, $query->getOptions());
    }

    /**
     * @test
     */
    public function ruleFatherTwoVariable()
    {
        $father = new Facts('father', 2);

        $father->is(['john', 'mike']);
        $father->is(['mike', 'paul']);
        $father->is(['mike', 'laure']);
        $father->is(['charles', 'jean']);


        $grandfather = new Rule('grandfather', function(Query $query) use($father) {

            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0], new Variable('Z')]))->apply($father),
                (new Query([new Variable('Z'), $query[1]]))->apply($father)
            );

            $query->setOptions($options);

            return $options->count() > 0;
        });

        $query = new Query([new Variable('Who1'), new Variable('Who2')]);
        self::assertSame(true, $grandfather($query));

        self::assertSame(
            [
                [
                    'Who1' => 'john',
                    'Who2' => 'paul',
                ],
                [
                    'Who1' => 'john',
                    'Who2' => 'laure',
                ],
            ],
            $query->getOptions()->toArray()
        );

    }


    /**
     * @test
     */
    public function ruleFilter()
    {
        $color = new Facts('color', 1);
        $color->is(['red']);
        $color->is(['blue']);
        $color->is(['green']);

        $neighboor = new Rule('neighbour', function(Query $query) use($color) {
            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0]]))->apply($color),
                (new Query([$query[1]]))->apply($color)
            );

            $query->setOptions($options);
            $query->filterOptions(function($x, $y){
                return $x->getValue() !== $y->getValue();
            });

            return $options->count() > 0;
        });

        $query = new Query([new Variable('Who'), 'green']);
        self::assertSame(true, $neighboor($query));

        self::assertSame(
            [
                [
                    'Who' => 'red',
                ],
                [
                    'Who' => 'blue',
                ],
            ],
            $query->getOptions()->toArray()
        );
    }


    /**
     * @test
     */
    public function ruleVariableFilter()
    {
        $color = new Facts('color', 1);
        $color->is(['red']);
        $color->is(['blue']);
        $color->is(['green']);

        $neighboor = new Rule('neighbour', function(Query $query) use($color) {
            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0]]))->apply($color),
                (new Query([$query[1]]))->apply($color)
            );

            $query->setOptions($options);
            $query->filterOptions(function($x, $y){
                return $x->getValue() !== $y->getValue();
            });

            return $options->count() > 0;
        });

        $query = new Query([new Variable('ColorA'), new Variable('ColorB')]);
        self::assertSame(true, $neighboor($query));

        self::assertSame(
            [
                [
                    'ColorA' => 'red',
                    'ColorB' => 'blue',
                ],
                [
                    'ColorA' => 'red',
                    'ColorB' => 'green',
                ],
                [
                    'ColorA' => 'blue',
                    'ColorB' => 'red',
                ],
                [
                    'ColorA' => 'blue',
                    'ColorB' => 'green',
                ],
                [
                    'ColorA' => 'green',
                    'ColorB' => 'red',
                ],
                [
                    'ColorA' => 'green',
                    'ColorB' => 'blue',
                ],
            ],
            $query->getOptions()->toArray()
        );
    }

    /**
     * @test
     */
    public function ruleMap()
    {
        $color = new Facts('color', 1);
        $color->is(['red']);
        $color->is(['blue']);
        $color->is(['green']);

        $neighboor = new Rule('neighbour', function(Query $query) use($color) {
            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0]]))->apply($color),
                (new Query([$query[1]]))->apply($color)
            );

            $query->setOptions($options);
            $query->filterOptions(function($x, $y){
                return $x->getValue() != $y->getValue();
            });

            return $options->count() > 0;
        });

        $country = new Rule('country', function(Query $query) use($neighboor) {
            $and = new AndLogic();
            $options = $and->unify(
                (new Query([$query[0], $query[1]]))->apply($neighboor),
                (new Query([$query[1], $query[2]]))->apply($neighboor)
            );

            $options = $and->unify(
                $options,
                (new Query([$query[0], $query[2]]))->apply($neighboor)
            );

            $query->setOptions($options);

            return $options->count() > 0;
        });



        $query = new Query(['blue', new Variable('R2'), new Variable('R3')]);

        self::assertSame(true, $country($query));


        self::assertSame(
            [
                [
                    'R2' => 'red',
                    'R3' => 'green',
                ],
                [
                    'R2' => 'green',
                    'R3' => 'red',
                ],
            ],
            $query->getOptions()->toArray()
        );
    }
}