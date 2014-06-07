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
        $product = new Product(array(
            'code'        => '9781449355739',
            'name'        => 'Learning Python, 5th Edition',
            'description' => 'Powerful Object-Oriented Programming'
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('BooksCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 100,
        );
        $product = new Product(array(
            'code'        => '9781449392772',
            'name'        => 'Programming PHP',
            'description' => 'Creating Dynamic Pages',
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('BooksCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 400,
        );
        $product = new Product(array(
            'code'        => '9780596516178',
            'name'        => 'The Ruby Programming Language',
            'description' => '',
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('BooksCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 7,
        );

        $product = new Product(array(
            'code'        => 'B0030GBSVG',
            'name'        => 'Strangers In The Night',
            'description' => 'The very best of Frank Sinatra'
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('MusicCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 1000,
        );
        $product = new Product(array(
            'code'        => 'B000002GYN',
            'name'        => 'Eagles',
            'description' => 'The very best of Eagles'
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('MusicCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 5000,
        );
        $product = new Product(array(
            'code'        => 'B000003TA4',
            'name'        => 'Nevermind',
            'description' => 'Nirvana Nevermind'
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('MusicCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 7000,
        );


        $product = new Product(array(
            'code'        => 'B00E3UN44W',
            'name'        => 'Sherlock',
            'description' => 'Modern version of one of the most greatest detectives of al times'
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('VideoCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 200,
        );
        $product = new Product(array(
            'code'        => 'B0041QSZFG',
            'name'        => 'Luther',
            'description' => ''
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('VideoCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 280,
        );
        $product = new Product(array(
            'code'        => 'B008M0P9I8',
            'name'        => 'Black Mirror',
            'description' => ''
        ));
        $product->setMainCategory($this->serviceManager
                                       ->get('VideoCategory'));
        $this->products[$product->getCode()] = array(
            'product' => $product,
            'stock'   => 110,
        );
    }
}
