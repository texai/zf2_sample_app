<?php

namespace Customer\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Customer\Address;

/**
 *
 */
class AddressFactory implements FactoryInterface
{
    private $data;

    public function __construct()
    {
        $this->data = array(
            array(
                'number'  => 145,
                'street'  => 'Featherstone Street',
                'city'    => 'London',
                'country' => 'England'
            ),
            array(
                'number'  => 61,
                'street'  => 'Wellfield Road',
                'city'    => 'Cardiff',
                'country' => 'Wales'
            ),
            array(
                'number'  => 41,
                'street'  => 'George Street',
                'city'    => 'London',
                'country' => 'England'
            ),
            array(
                'number'  => 46,
                'street'  => 'Morningside Road',
                'city'    => 'Edinburgh',
                'country' => 'Scotland'
            ),
            array(
                'number'  => 27,
                'street'  => 'Colmore Road',
                'city'    => 'Birmingham',
                'country' => 'England'
            ),
        );
    }

    /**
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $index = mt_rand(0, 4);
        return new Address($this->data[$index]);
    }
}
