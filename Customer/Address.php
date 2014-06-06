<?php

namespace Customer;

/**
 *
 */
class Address
{
    private $number;

    private $street;

    private $city;

    private $country;

    /**
     *
     */
    public function __construct(array $options = array())
    {
        $this->number  = (isset($options['number'])) ? $options['number'] : null;
        $this->street  = (isset($options['street'])) ? $options['street'] : null;
        $this->city    = (isset($options['city'])) ? $options['city'] : null;
        $this->country = (isset($options['country'])) ? $options['country'] : null;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }
}
