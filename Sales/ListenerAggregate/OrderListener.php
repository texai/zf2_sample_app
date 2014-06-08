<?php

namespace Sales\ListenerAggregate;

use Zend\EventManager\SharedEventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;

/**
 *
 */
class OrderListener implements SharedListenerAggregateInterface
{
    private $listeners = array();

    /**
     *
     */
    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('Sales\Order', 'setItems', array($this, 'setTotal'));
        $this->listeners[] = $events->attach('Sales\Order', 'addItem.pre', array($this, 'setItemDiscount'), 50);
        $this->listeners[] = $events->attach('Sales\Order', 'addItem.post', array($this, 'updateTotal'), -50);
        $this->listeners[] = $events->attach('Sales\Order', 'setCustomer', array($this, 'setBillingAddress'));
    }

    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach('Sales\Order', $listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function setTotal(EventInterface $event)
    {
        $order = $event->getTarget();
        $items = $event->getParam('items');
        $total = 0.00;
        foreach ($items as $item) {
            $total += $item->getTotal();
        }
        $order->setTotal($total);
    }

    public function setItemDiscount(EventInterface $event)
    {
        $order   = $event->getTarget();
        $item    = $event->getParam('item');
        $product = $item->getProduct();
        $item->setPrice($product->getPrice());
        if ($product->hasDiscount()) {
           $item->setDiscountPercentage($product->getDiscountPercentage());
        }
    }

    public function updateTotal(EventInterface $event)
    {
        $order = $event->getTarget();
        $item  = $event->getParam('item');
        $total = $order->getTotal();
        $total += $item->getTotal();
        $order->setTotal($total);
    }

    public function setBillingAddress(EventInterface $event)
    {
        $order    = $event->getTarget();
        $customer = $event->getParam('customer');
        if (!$customer->hasBillingAddress()) {
            $event->stopPropagation(true);
            $response = new ResponseCollection();
            $response->setStopped(true);
            return $response;
        }
        $order->setBillingAddress($customer->getBillingAddress());
    }
}
