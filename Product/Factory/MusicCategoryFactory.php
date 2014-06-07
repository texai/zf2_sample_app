<?php

namespace Product\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Product\Category;

/**
 *
 */
class MusicCategoryFactory implements FactoryInterface
{

    /**
     *
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Category(array(
            'code'        => 'CTMUK',
            'name'        => 'Music',
            'description' => 'CDs, Vinyl, and other sound recordings'
        ));
    }
}
