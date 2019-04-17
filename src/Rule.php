<?php


namespace Logic;


class Rule
{
    /**
     * @var callable
     */
    private $rule;
    private $name;

    public function __construct($name, callable $rule)
    {
        $this->rule = $rule;
        $this->name = $name;
    }

    public function __invoke($query)
    {
        return ($this->rule)($query);
    }
}