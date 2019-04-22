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
        try {
            return $runner->run();
        } catch (\Error $e) {
            if ($this->isMaximumFunctionNesting($e)) {
                return new Solutions();
            }

            throw $e;
        }

    }

    private function isMaximumFunctionNesting(\Error $e)
    {
        return strpos($e->getMessage(), 'Maximum function nesting level of') !== false;
    }
}
