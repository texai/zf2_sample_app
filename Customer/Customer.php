<?php

namespace Customer;

/**
 *
 */
class Customer
{
    private $firstName;

    private $surname;

    private $email;

    private $telephone;

    private $addresses;

    /**
     *
     */
    public function __construct(array $options = array())
    {
        $this->firstName = (isset($options['first_name'])) ? $options['first_name'] : null;
        $this->surname   = (isset($options['surname'])) ? $options['surname'] : null;
        $this->email     = (isset($options['email'])) ? $options['email'] : null;
        $this->telephone = (isset($options['telephone'])) ? $options['telephone'] : null;
        $this->addresses = (isset($options['addresses'])) ? $options['addresses'] : array();
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function getAddresses($type = 'all')
    {
        if ('all'     === $type) return $this->addresses;
        if ('billing' === $type) return $this->addresses['billing'];
        if ('contact' === $type) return $this->addresses['contact'];
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function setAddresses(array $addresses)
    {
        $this->addresses = $addresses;
        return $this;
    }
}
