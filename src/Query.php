<?php


namespace Logic;


class Query extends \ArrayObject
{

    /**
     * @var Options
     */
    private $options;

    /**
     * Query constructor.
     */
    public function __construct(array $input)
    {
        $arguments = [];
        foreach ($input as $argument) {
            if (!$argument instanceof Argument) {
                $argument = new Constant($argument);
            }
            $arguments[] = $argument;
        }
        parent::__construct($arguments);
        $this->options = new Options();
    }


    public function appendOption(Option $rowResult)
    {
        $this->options[] = $rowResult;
    }


    public function getOptions()
    {
        return $this->options;
    }


    public function filterOptions(\Closure $function)
    {

        $rows = [];
        foreach ($this->options as $item) {
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

        $filtered = new Options();

        foreach ($rows as $row) {
            if ($function(...$row['data'])) {
                $filtered[] = $row['item'];
            }
        }

        $this->options = $filtered;
    }

    public function apply($clause)
    {
        $clause($this);
        return $this->options;
    }

    public function argumentExists($argumentName)
    {
        foreach ($this as $argument) {
            if ($argumentName == $argument->getName()) {
                return true;
            }
        }

        return false;
    }

    public function setOptions(Options $options)
    {
        $newOptions = new Options();
        foreach ($options as $option) {
            $newOption = new Option();
            /** @var Argument $argument */
            foreach ($option as $argument) {
                if ($this->argumentExists($argument->getName())) {
                    $newOption[] = $argument;
                }
            }

            $newOptions[] = $newOption;
        }
        $this->options = $newOptions;
    }
}