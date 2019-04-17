<?php


namespace Logic;


class Option extends \ArrayObject
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

    public function match(Option $optionB)
    {
        if ($optionB->containsOnliConstants()) {
            return $this;
        }

        if ($this->containsOnliConstants()) {
            return $optionB;
        }


        $newOption = new Option();
        $matchExists = true;
        /** @var Variable $argument */
        foreach ($this as $index => $argument) {
            /** @var Variable $argumentB */
            foreach ($optionB as $indexB => $argumentB) {
                if ($argument->getName() === $argumentB->getName()) {

                    if (!$argument->same($argumentB)) {
                        $matchExists = false;
                    }
                }
            }
        }

        if (!$matchExists) {
            return $newOption;
        }

        foreach ($this as $index => $argument) {
                $newOption[] = $argument;

        }

        foreach ($optionB as $index => $argument) {
                $newOption[] = $argument;
        }

        return $newOption;

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