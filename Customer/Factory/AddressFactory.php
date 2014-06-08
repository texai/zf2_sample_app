<?php

namespace Customer\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Customer\Address;
use Geo\Country;

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
                'country' => new Country('England', 'ENG'),
            ),
            array(
                'number'  => 61,
                'street'  => 'Wellfield Road',
                'city'    => 'Cardiff',
                'country' => new Country('Wales', 'WLS'),
            ),
            array(
                'number'  => 41,
                'street'  => 'George Street',
                'city'    => 'London',
                'country' => new Country('England', 'ENG'),
            ),
            array(
                'number'  => 46,
                'street'  => 'Morningside Road',
                'city'    => 'Edinburgh',
                'country' => new Country('Scotland', 'SCT'),
            ),
            array(
                'number'  => 27,
                'street'  => 'Colmore Road',
                'city'    => 'Birmingham',
                'country' => new Country('England', 'ENG'),
            ),
            array(
                'number'  => 3459,
                'street'  => 'Av. Aramburú, San Isidro',
                'city'    => 'Lima',
                'country' => new Country('Peru', 'PE'),
            ),
            array(
                'number'  => 92-23,
                'street'  => 'Theodorstraße',
                'city'    => 'Hamburg',
                'country' => new Country('Germany', 'DE'),
            ),
            array(
                'number'  => 1911,
                'street'  => 'Saint Nicholas Avenue',
                'city'    => 'New York',
                'country' => new Country('United States', 'US'),
            ),
        );
    }

    /**
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $index = mt_rand(0, 7);
        return new Address($this->data[$index]);
    }
}
