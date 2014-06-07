<?php

namespace Product\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Product\Category;

/**
 *
 */
class BooksCategoryFactory implements FactoryInterface
{

    /**
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Category(array(
            'code'        => 'CTBUK',
            'name'        => 'Books',
            'description' => 'Books, Calendars, Card Decks, Sheet Music, Magazine, Journals'
        ));
    }
}
