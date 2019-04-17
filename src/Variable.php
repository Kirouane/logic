<?php


namespace Logic;


class Variable extends Argument
{

    public function __construct($name, $value = null)
    {
        $this->setName($name);
        $this->setValue($value);
    }
}