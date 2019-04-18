<?php


namespace Logic;


class Arguments extends \ArrayObject
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