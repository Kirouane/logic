<?php


use Logic\Logic;
use Logic\Variable;
use PHPUnit\Framework\TestCase;

class FamilyTreeTest extends TestCase
{
    /**
     * @test
     */
    public function scenario()
    {
        $logic = new Logic();
        $man = $logic->facts('man', 1);

        $man->is('andre');
        $man->is('bernard');
        $man->is('babar');
        $man->is('clement');
        $man->is('dudulle');
        $man->is('damien');
        $man->is('baptiste');
        $man->is('cedric');
        $man->is('didier');
        $man->is('dagobert');

        $woman = $logic->facts('woman', 1);

        $woman->is('augustine');
        $woman->is('becassine');
        $woman->is('brigitte');
        $woman->is('chantal');
        $woman->is('celestine');
        $woman->is('caroline');
        $woman->is('charlotte');
        $woman->is('daniela');
        $woman->is('dominique');

        $child = $logic->facts('child', 2);

        $child->is('bernard', 'andre');
        $child->is('bernard', 'augustine');
        $child->is('babar', 'andre');
        $child->is('babar', 'augustine');
        $child->is('brigitte', 'andre');
        $child->is('brigitte', 'augustine');
        $child->is('clement', 'bernard');
        $child->is('clement', 'becassine');
        $child->is('celestine', 'babar');
        $child->is('caroline', 'brigitte');
        $child->is('caroline', 'baptiste');
        $child->is('cedric', 'brigitte');
        $child->is('cedric', 'baptiste');
        $child->is('dudulle', 'clement');
        $child->is('dudulle', 'chantal');
        $child->is('damien', 'clement');
        $child->is('damien', 'chantal');
        $child->is('daniela', 'clement');
        $child->is('daniela', 'chantal');
        $child->is('didier', 'cedric');
        $child->is('didier', 'charlotte');
        $child->is('dagobert', 'cedric');
        $child->is('dagobert', 'charlotte');
        $child->is('dominique', 'cedric');
        $child->is('dominique', 'charlotte');


        $parent = $logic->rule('parent', function($x, $y) use($child) {
            return $child($y, $x);
        });

        $son = $logic->rule('son', function($x, $y) use($child, $man) {
            return $this->andLogic($child($y, $x), $man($x));
        });

        $daughter = $logic->rule('daughter', function($x, $y) use($child, $woman) {
            return $this->andLogic($child($y, $x), $woman($x));
        });

        $ancestor = $logic->rule('ancestor', function($x, $y) use($parent) {
            $z = new Variable();
            return $this->orLogic(
                $parent($x, $y),
                $this->andLogic($parent($x, $z), $this->prepare($z, $y))
            );
        });

        self::assertSame(
            [
                [
                    '_R' => 'baptiste',
                ]
            ],
            $son('_R', 'cedric')->toArray()
        );

        self::assertSame(
            [
                [
                    '_R' => 'brigitte',
                ]
            ],
            $daughter('_R', 'cedric')->toArray()
        );

        self::assertSame(
            [
                [
                    '_R' => 'brigitte',
                ],
                [
                    '_R' => 'baptiste',
                ],
                [
                    '_R' => 'andre',
                ],
                [
                    '_R' => 'augustine',
                ]
            ],
            $ancestor('_R', 'cedric')->toArray()
        );
    }
}