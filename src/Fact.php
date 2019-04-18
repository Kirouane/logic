<?php


namespace Logic;


class Fact extends \ArrayObject
{
    public function __construct(array $arguments)
    {
        parent::__construct(array_map(function($argument) {
            return new Constant($argument);
        }, $arguments));
    }

    public function matches($query)
    {
        $solution = new Solution();
        foreach ($this as $i => $argument) {
            /** @var Variable $variable */
            if ($query[$i] instanceof Variable) {
                $variable = clone $query[$i];
                $variable->setValue($argument->getValue());

                if (isset($solution[$variable->getName()]) && $solution[$variable->getName()]->getValue() !== $variable->getValue()) {
                    return new Solution();
                }

                $solution[$variable->getName()] = $variable;
            } elseif (!$argument->equals($query[$i])) {
                return new Solution();
            } else {
                $solution[$argument->getName()] = clone $argument;
            }
        }

        return $solution;
    }
}