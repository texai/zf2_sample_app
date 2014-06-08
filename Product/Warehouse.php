<?php

namespace Product;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Geo\Country;
use Product\Product;

/**
 *
 */
class Warehouse implements ServiceLocatorAwareInterface
{
    private $serviceManager;

    private $country;

    private $products;

    public function __construct(Country $country)
    {
        $this->country  = $country;
        $this->products = array();
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

    public function getCountry()
    {
        return $this->country;
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

    public function setCountry(Country $country)
    {
        $this->country = $country;
        return $this;
    }

    public function isInStock(Product $product, $quantity)
    {
        $stock = $this->products[$product->getCode()]['stock'];
        return ($stock >= $quantity);
    }

    public function dispatch(Product $product, $quantity)
    {
        $this->products[$product->getCode()]['stock'] -= $quantity;
    }
}
