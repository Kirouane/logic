<?php


namespace Logic;


class Rule implements Clause
{
    private $rule;

    public function __construct(callable $rule)
    {
        $this->rule = $rule;
    }

    public function __invoke(...$argments)
    {
        if ($this->isMaximumFunctionNestingReached()) {
            return new Solutions();
        }
        $runner = new RuleRunner($this, new Arguments($argments), $this->rule);

        return $runner->run();

    }

    private function isMaximumFunctionNestingReached()
    {
        return xdebug_get_stack_depth() > ini_get('xdebug.max_nesting_level') - 20;
    }
}
