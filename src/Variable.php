<?php


namespace Logic;


class Variable extends Argument
{
    public function __construct($name = null, $value = null)
    {
        $this->setName($name ?: mt_rand());
        $this->setValue($value);
    }
}