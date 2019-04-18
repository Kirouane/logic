<?php


namespace Logic;


class Rule implements Clause
{
    private $rule;
    private $name;

    public function __construct($name, callable $rule)
    {
        $this->rule = $rule;
        $this->name = $name;
    }

    public function __invoke(...$argments)
    {
        $runner = new RuleRunner($this, new Arguments($argments), $this->rule);
        return $runner->run();
    }
}