<?php


namespace Logic;


class Constant extends Argument
{
    public function __construct($value, $name = null)
    {
        $this->setName($name ?: '_' . mt_rand());
        $this->setValue($value);
    }
}