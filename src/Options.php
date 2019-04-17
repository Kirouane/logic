<?php


namespace Logic;


class Options extends \ArrayObject
{

    public function toArray()
    {
        $array = [];
        /** @var Option $option */
        foreach ($this as $option) {
            $array[] = $option->toArray();
        }

        return $array;
    }

    public function expand()
    {
        $array = [];
        /** @var Option $option */
        foreach ($this as $option) {
            $array[] = $option->expand();
        }

        return $array;
    }
}