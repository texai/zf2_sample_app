<?php

namespace Product;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 */
class Warehouse implements ServiceLocatorAwareInterface
{
    private $serviceManager;

    private $products;

    public function __construct()
    {
        $this->products = array();
    }

    public function getServiceLocator()
    {
        return $this->serviceManager;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceManager = $serviceLocator;
        return $this;
    }

    public function init()
    {
        while (9 > count($this->products)) {
            $product = $this->serviceManager->get('Product');
            if (!isset($this->products[$product->getCode()])) {
                $this->products[$product->getCode()] = array(
                    'product' => $product,
                    'stock'   => mt_rand(0, 100),
                );
            }
        }
    }
}
