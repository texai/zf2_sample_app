<?php

namespace Product\Factory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Geo\Country;
use Product\Warehouse;

/**
 *
 */
class WarehouseAbstractFactory implements AbstractFactoryInterface
{
    private $backupCountries;

    public function __construct()
    {
        $this->backupCountries = array(
            'Berlin'       => array('DE' => 'Germany'),
            'RioDeJaneiro' => array('BR' => 'Brazil'),
            'Tokio'        => array('JP' => 'Japan'),
            'Nuuk'         => array('GL' => 'Greenland'),
        );
    }

    /**
     *
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $matched = preg_match(
            '/^(?P<city>[A-Z][a-zA-Z]+)Warehouse$/',
            $requestedName,
            $matches
        );
        return ($matched && isset($this->backupCountries[$matches['city']]));
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        list($code, $name) = each($this->backupCountries[str_replace('Warehouse', '', $requestedName)]);
        $service = new Warehouse(new Country($name, $code));
        $service->setServiceLocator($serviceLocator)
                ->init();
        return $service;
    }
}
