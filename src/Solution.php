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
        if ($solutionB->containsOnliConstants()) {
            return $this;
        }

        if ($this->containsOnliConstants()) {
            return $solutionB;
        }


        $newSolution = new Solution();
        $matchExists = true;
        /** @var Variable $argument */
        foreach ($this as $index => $argument) {
            /** @var Variable $argumentB */
            foreach ($solutionB as $indexB => $argumentB) {
                if ($argument->getName() === $argumentB->getName()) {

                    if (!$argument->same($argumentB)) {
                        $matchExists = false;
                    }
                }
            }
        }

        if (!$matchExists) {
            return $newSolution;
        }

        foreach ($this as $index => $argument) {
                $newSolution[$argument->getName()] = $argument;

        }

        foreach ($solutionB as $index => $argument) {
                $newSolution[$argument->getName()] = $argument;
        }

        return $newSolution;

    }

    public function containsOnliConstants()
    {

        foreach ($this as $argument) {
            if ($argument instanceof Variable) {
                return false;
            }
        }

        return true;

    }

    public function find($argumentName)
    {
        foreach ($this as $argument) {

            if ($argument->getName() === $argumentName) {
                return $argument;
            }
        }

        return null;
    }

}