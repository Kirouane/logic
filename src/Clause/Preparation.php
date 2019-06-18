<?php
namespace Logic\Clause;


class Preparation
{
    /**
     * @var callable
     */
    private $function;

    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    public function run()
    {
        return ($this->function)();
    }

}