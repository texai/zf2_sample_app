<?php

namespace Product;

class Category
{
    private $code;

    private $name;

    private $description;

    public function __construct(array $options = array())
    {
        $this->code        = (isset($options['code'])) ? $options['code'] : null;
        $this->name        = (isset($options['name'])) ? $options['name'] : null;
        $this->description = (isset($options['description'])) ? $options['description'] : null;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $ths->description;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}
