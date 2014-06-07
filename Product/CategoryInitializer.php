<?php

namespace Product;

use DateTime;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\InitializerInterface;

/**
 *
 */
class CategoryInitializer implements InitializerInterface
{

    /**
     *
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof \Product\Category) {
            $config           = $serviceLocator->get('Config');
            $discountPolicies = $config['categories']['discount_policies'][$instance->getCode()];
            $now              = new DateTime();
            $day              = $now->format('D');
            $discount         = (isset($discountPolicies[$day])) ? $discountPolicies[$day] : 0.00;
            $instance->setDiscount($discount);
        }
    }
}
