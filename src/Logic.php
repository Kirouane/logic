<?php


namespace Logic;


class Logic
{
    private $factsContainers = [];
    private $ruleContainers = [];

    public function facts(string $name, $arity)
    {
        return $this->factsContainers[$name] = new Facts($name, $arity);
    }

    public function rule(string  $name, callable $ruleFunction)
    {
        return $this->ruleContainers[$name] = new Rule($name, $ruleFunction);
    }
}