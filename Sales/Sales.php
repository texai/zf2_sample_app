<?php

namespace Sales;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

/**
 *
 */
class Sales implements EventManagerAwareInterface
{
    private $orders;

    private $warehouses;

    private $eventManager;

    /**
     *
     */
    public function __construct()
    {
        $this->orders = array();
        $this->warehouses = array();
    }

    public function getWarehouses()
    {
        return $this->warehouses;
    }

    public function setWarehouses(array $warehouses)
    {
        $this->warehouses = $warehouses;
        return $this;
    }

    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->eventManager = new EventManager(array(
                __CLASS__,
                get_called_class(),
            ));
        }
        return $this->eventManager;
    }

    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    public function createOrder(array $data = array())
    {
        $order = new Order($data);
        $hash  = spl_object_hash($order);
        $this->orders[$hash] = $order;
        return $order;
    }

    public function addItem(Order $order, Item $item)
    {
        $params = compact('order', 'item');
        $response = $this->getEventManager()
                         ->trigger(__FUNCTION__, $this, $params);
        if ($response->stopped()) {
            $product = $item->getProduct();
            throw new \Exception(sprintf(
                "Product %s is out of stock at this moment :(",
                $product->getName() . '(' . $product->getCode() . ')'
            ));
        }
        $hash = spl_object_hash($order);
        $this->orders[$hash]->addItem($item);
        return $this;
    }
}
