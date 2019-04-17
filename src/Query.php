<?php


namespace Logic;


class Query extends \ArrayObject
{

    /**
     * @var Solutions
     */
    private $solutions;

    /**
     * Query constructor.
     */
    public function __construct(array $input)
    {
        $arguments = [];
        foreach ($input as $argument) {

            if (!$argument instanceof Argument) {

                if (substr($argument, '0', 1) === '_') {
                    $argument = new Variable($argument);
                } else {
                    $argument = new Constant($argument);
                }
            }
            $arguments[] = $argument;
        }
        parent::__construct($arguments);
        $this->solutions = new Solutions();
    }


    public function getSolutions()
    {
        return $this->solutions;
    }


    public function filterSolutions(\Closure $function)
    {

        $rows = [];
        foreach ($this->solutions as $item) {
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

        $this->solutions = $filtered;
    }

    public function apply($clause)
    {
        $clause($this);
        return $this->solutions;
    }

    public function argumentExists($argumentName)
    {
        foreach ($this as $argument) {
            if ($argumentName === $argument->getName()) {
                return true;
            }
        }

        return false;
    }
}