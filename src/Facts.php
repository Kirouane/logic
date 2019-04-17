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

    public function is(array $constants)
    {
        if (count($constants) !== $this->arity) {
            throw new \OverflowException('Unexpected arity');
        }

        $arguments = [];
        foreach ($constants as $argument) {
            $argument = new Constant($argument);
            $arguments[] = $argument;
        }

        $this->append($arguments);
    }


    public function __invoke(Query $query)
    {
        return $this->filter(function ($arguments) use($query) {
            $option = new Option();
            /** @var Argument $argument */
            foreach ($arguments as $i => $argument) {
                /** @var Variable $variable */

                if ($query[$i] instanceof Variable) {
                    $variable = clone $query[$i];
                    $variable->setValue($argument->getValue());
                    $option[] = $variable;
                } elseif (!$argument->equals($query[$i])) {
                    return false;
                } else {
                    $option[] = clone $argument;
                }
            }

            $query->appendOption($option);

            return true;
        })->count() > 0;
    }

    private function filter(callable $callable): Facts
    {
        $newFacts = new Facts($this->name, $this->arity);
        $newFacts->exchangeArray(
            \array_values(\array_filter($this->getArrayCopy(), $callable))
        );
        return $newFacts;
    }

}