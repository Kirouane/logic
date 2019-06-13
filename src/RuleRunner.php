<?php


namespace Logic;


use Logic\Unification\AndLogic;
use Logic\Unification\OrLogic;

class RuleRunner
{
    /**
     * @var Arguments
     */
    private $arguments;
    /**
     * @var callable
     */
    private $function;
    /**
     * @var Rule
     */
    private $rule;

    public function __construct(Rule $rule, Arguments $arguments, callable $function)
    {
        $this->arguments = $arguments;
        $this->function = $function->bindTo($this);
        $this->rule = $rule;
    }

    public function __invoke(...$argments)
    {
        return ($this->rule)(...$argments);
    }

    public function run()
    {
        return $this->filterVariables(
            ($this->function)(...$this->arguments)
        );
    }

    public function prepare(...$arguments): callable
    {

        return function() use($arguments) {
            return ($this->rule)(...$arguments);
        };
    }


    public function andLogic(...$clauses)
    {
        if (count($clauses) < 2) {
            throw new \InvalidArgumentException('And operator need at least 2 clauses');
        }

        $result = reset($clauses);

        if (is_callable($result) && !$result instanceof Clause) {
            $result = $result();
            if (!$result->count()) {
                return new Solutions();
            }
        }

        $unification = new AndLogic();
        $i = 0;
        foreach ($clauses as $clause) {
            $i++;
            if ($i === 1) {
                continue;
            }

            if (is_callable($clause) && !$clause instanceof Clause) {
                $clause = $clause();
                if (!$clause->count()) {
                    return new Solutions();
                }
            }

            $result = $unification->unify($result, $clause);
        }

        return $result;
    }


    public function orLogic(...$clauses)
    {
        if (count($clauses) < 2) {
            throw new \InvalidArgumentException('And operator need at least 2 clauses');
        }

        $result = reset($clauses);
        $unification = new OrLogic();
        foreach ($clauses as $clause) {
            if ($clause === $result) {
                continue;
            }

            $result = $unification->unify($result, $clause);
        }

        return $result;
    }

    private function filterVariables(Solutions $solutions)
    {
        $newSolutions = new Solutions();
        foreach ($solutions as $solution) {
            $newSolution = new Solution();
            /** @var Argument $argument */
            foreach ($solution as $argument) {
                if ($this->arguments->argumentExists($argument->getName())) {
                    $newSolution[$argument->getName()] = $argument;
                }
            }

            $newSolutions[] = $newSolution;
        }
        return $newSolutions;
    }


    public function filter(Solutions $solutions, \Closure $function)
    {

        $rows = [];
        foreach ($solutions as $item) {
            $r = [];
            foreach ($this->arguments as $arg) {
                if ($arg instanceof Variable) {
                    $r[] = $item[$arg->getName()]->getValue();
                } else {
                    $r[] = $arg->getValue();
                }
            }
            $rows[] = [
                'data' => $r,
                'item' => $item
            ];
        }

        $filtered = new Solutions();

        foreach ($rows as $row) {
            if ($function(...$row['data'])) {
                $filtered[] = $row['item'];
            }
        }

        return $filtered;
    }

}