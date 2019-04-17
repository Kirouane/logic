<?php


namespace Logic;


use Logic\Unification\AndLogic;

class RuleRunner
{
    /**
     * @var Query
     */
    private $query;
    /**
     * @var callable
     */
    private $rule;

    public function __construct(Query $query, callable $rule)
    {
        $this->query = $query;
        $this->rule = $rule->bindTo($this);

    }

    public function run()
    {
        return $this->filterVariables(
            ($this->rule)(...$this->query)
        );
    }

    public function andLogic($clauseA, $clauseB)
    {
        return (new AndLogic())->unify($clauseA, $clauseB);
    }

    private function filterVariables(Solutions $solutions)
    {
        $newSolutions = new Solutions();
        foreach ($solutions as $solution) {
            $newSolution = new Solution();
            /** @var Argument $argument */
            foreach ($solution as $argument) {
                if ($this->query->argumentExists($argument->getName())) {
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
            foreach ($this->query as $arg) {
                if ($arg instanceof Variable) {
                    $r[] = $item->find($arg->getName())->getValue();
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