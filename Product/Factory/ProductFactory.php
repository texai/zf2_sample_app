<?php

namespace Product\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use DateTime;
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
        $now = new DateTime();
        $data = array(
            'Mon' => range(1500, 3000, 10),
            'Tue' => range(1600, 3500, 10),
            'Wed' => range(1000, 2000, 10),
            'Thu' => range(1400, 2000, 10),
            'Fri' => range(500, 1000, 10),
            'Sat' => range(2500, 5000, 10),
            'Sun' => range(3000, 6000, 10),
        );
        $getPrice = function ($data, $datetime) {
            $day   = $datetime->format('D');
            $index = mt_rand(0, count($data[$day]) - 1);
            return $data[$day][$index];
        };
        $this->data = array(
            array(
                'code'        => '9781449355739',
                'name'        => 'Learning Python, 5th Edition',
                'description' => 'Powerful Object-Oriented Programming',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => '9781449392772',
                'name'        => 'Programming PHP',
                'description' => 'Creating Dynamic Pages',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => '9780596516178',
                'name'        => 'The Ruby Programming Language',
                'description' => '',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => 'B0030GBSVG',
                'name'        => 'Strangers In The Night',
                'description' => 'The very best of Frank Sinatra',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => 'B000002GYN',
                'name'        => 'Eagles',
                'description' => 'The very best of Eagles',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => 'B000003TA4',
                'name'        => 'Nevermind',
                'description' => 'Nirvana Nevermind',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => 'B00E3UN44W',
                'name'        => 'Sherlock',
                'description' => 'Modern version of one of the most greatest detectives of all times',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => 'B0041QSZFG',
                'name'        => 'Luther',
                'description' => '',
                'price'       => $getPrice($data, $now),
            ),
            array(
                'code'        => 'B008M0P9I8',
                'name'        => 'Black Mirror',
                'description' => '',
                'price'       => $getPrice($data, $now),
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
