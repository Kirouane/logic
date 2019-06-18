<?php

namespace Logic;

use Logic\Clause\Preparation;

class Facts extends \ArrayObject implements Clause
{

    /**
     * @var int
     */
    private $arity;

    /** @noinspection MagicMethodsValidityInspection */


    public function is(...$constants)
    {
        if ($this->arity > 0 && count($constants) !== $this->arity) {
            throw new \OverflowException('Unexpected arity');
        }

        $this->append(new Fact($constants));
    }

    public function __invoke(...$arguments)
    {
        $solutions = new Solutions();
        /** @var Fact $fact */
        foreach ($this as $fact) {
            $solution = $fact->matches(new Arguments($arguments));
            if ($solution->count()) {
                $solutions[] = $solution;
            }
        }

        return $solutions;
    }

    public function prepare(...$arguments)
    {
        return new Preparation(function() use($arguments) {
            return ($this)(...$arguments);
        });
    }


}