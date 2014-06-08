<?php

namespace Geo;

/**
 *
 */
class Country
{
    private $name;

    private $code;

    /**
     *
     */
    public function __construct($name, $code)
    {
        $this->name = $name;
        $this->code = $code;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }
}
