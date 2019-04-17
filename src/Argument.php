<?php


namespace Logic;


class Argument
{

    private $name;

    private $value;

    /**
     * @param $value
     * @return Argument
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function same(Argument $variable)
    {
        return $variable->getName() === $this->getName() && $variable->getValue() === $this->getValue();
    }

    public function equals(Argument $variable)
    {
        return $variable->getValue() === $this->getValue();
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}