<?php


namespace Logic;


use Logic\Unification\AndLogic;

class Rule
{
    private $rule;
    private $name;

    public function __construct($name, callable $rule)
    {
        $this->rule = $rule;
        $this->name = $name;
    }

    public function __invoke(...$queryArgs)
    {
        $query = new Query($queryArgs);
        $runner = new RuleRunner($query, $this->rule);
        return $runner->run();

        return $this->filterVariables(
            ($this->rule)($this, ...$query),
            $query
        );
    }

    public function andLogic($clauseA, $clauseB)
    {
        return (new AndLogic())->unify($clauseA, $clauseB);
    }

    private function filterVariables(Solutions $solutions, Query $query)
    {
        $newSolutions = new Solutions();
        foreach ($solutions as $solution) {
            $newSolution = new Solution();
            /** @var Argument $argument */
            foreach ($solution as $argument) {
                if ($query->argumentExists($argument->getName())) {
                    $newSolution[$argument->getName()] = $argument;
                }
            }

            $newSolutions[] = $newSolution;
        }
        return $newSolutions;
    }


    public function filterSolutions(Solutions $solutions, \Closure $function)
    {

        $rows = [];
        foreach ($solutions as $item) {
            $r = [];
            foreach ($this as $arg) {
                if ($arg instanceof Variable) {
                    $r[] = $item->find($arg->getName());
                } else {
                    $r[] = $arg;
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