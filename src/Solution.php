<?php


namespace Logic;


class Solution extends \ArrayObject
{

    public function toArray()
    {
        $array = [];
        /** @var Variable $variable */
        foreach ($this as $variable) {
            if ($variable instanceof Constant) {
                continue;
            }

            $array[$variable->getName()] = $variable->getValue();
        }

        return $array;
    }

    public function expand()
    {
        $array = [];
        /** @var Variable $variable */
        foreach ($this as $variable) {
            $array[$variable->getName()] = $variable->getValue();
        }

        return $array;
    }

    public function match(Solution $solutionB)
    {
        $newSolution = new Solution();
        /** @var Variable $argument */
        foreach ($this as $index => $argument) {
            $argumentB = $solutionB[$argument->getName()] ?? null;
            if ($argumentB && !$argument->same($argumentB)) {
                return $newSolution;
            }
        }

        foreach ($this as $index => $argument) {
            $newSolution[$argument->getName()] = $argument;
        }

        foreach ($solutionB as $index => $argument) {
            $newSolution[$argument->getName()] = $argument;
        }

        return $newSolution;

    }


}