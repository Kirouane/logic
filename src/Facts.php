<?php

namespace Logic;

class Facts extends \ArrayObject
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $arity;

    /** @noinspection MagicMethodsValidityInspection */

    /**
     * Fact constructor.
     * @param string $name
     * @param int $arity
     */
    public function __construct($name, $arity)
    {
        parent::__construct([]);
        $this->name = $name;
        $this->arity = $arity;

    }

    public function is(...$constants)
    {
        if (count($constants) !== $this->arity) {
            throw new \OverflowException('Unexpected arity');
        }

        $this->append(new Fact($constants));
    }

    public function __invoke(...$arguments)
    {
        $solutions = new Solutions();
        $query = new Query($arguments);

        foreach ($this as $fact) {
            $solution = $fact->matches($query);
            if ($solution->count()) {
                $solutions[] = $solution;
            }
        }

        return $solutions;
    }

}