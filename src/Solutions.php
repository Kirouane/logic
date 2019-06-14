<?php


namespace Logic;


class Solutions extends \ArrayObject
{

    public function toArray()
    {
        $array = [];
        /** @var Solution $solution */
        foreach ($this as $solution) {
            $array[] = $solution->toArray();
        }

        return $array;
    }

    public function expand()
    {
        $array = [];
        /** @var Solution $solution */
        foreach ($this as $solution) {
            $array[] = $solution->expand();
        }

        return $array;
    }

    public function found(): bool
    {
        return $this->count() > 0;
    }
}