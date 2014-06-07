<?php

namespace Product\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Product\Product;

/**
 *
 */
class ProductFactory implements FactoryInterface
{
    private $data;

    /**
     *
     */
    public function __construct()
    {
        $this->data = array(
            array(
                'code'        => '9781449355739',
                'name'        => 'Learning Python, 5th Edition',
                'description' => 'Powerful Object-Oriented Programming'
            ),
            array(
                'code'        => '9781449392772',
                'name'        => 'Programming PHP',
                'description' => 'Creating Dynamic Pages',
            ),
            array(
                'code'        => '9780596516178',
                'name'        => 'The Ruby Programming Language',
                'description' => '',
            ),
            array(
                'code'        => 'B0030GBSVG',
                'name'        => 'Strangers In The Night',
                'description' => 'The very best of Frank Sinatra'
            ),
            array(
                'code'        => 'B000002GYN',
                'name'        => 'Eagles',
                'description' => 'The very best of Eagles'
            ),
            array(
                'code'        => 'B000003TA4',
                'name'        => 'Nevermind',
                'description' => 'Nirvana Nevermind'
            ),
            array(
                'code'        => 'B00E3UN44W',
                'name'        => 'Sherlock',
                'description' => 'Modern version of one of the most greatest detectives of al times',
            ),
            array(
                'code'        => 'B0041QSZFG',
                'name'        => 'Luther',
                'description' => ''
            ),
            array(
                'code'        => 'B008M0P9I8',
                'name'        => 'Black Mirror',
                'description' => ''
            ),
        );
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $index = mt_rand(0, 8);
        $category = $serviceLocator->get('BooksCategory');
        if (3 <= $index && 5 >= $index) {
            $category = $serviceLocator->get('MusicCategory');
        } elseif (6 <= $index) {
            $category = $serviceLocator->get('VideoCategory');
        }
        $product = new Product($this->data[$index]);
        $product->setMainCategory($category);
        return $product;
    }
}
