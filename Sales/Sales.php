<?php

namespace Sales;

/**
 *
 */
class Sales
{
    private $orders;

    /**
     *
     */
    public function __construct()
    {
        $this->orders = array();
    }

    public function createOrder(array $data)
    {
        $order = new Order($data);
        $hash  = spl_object_hash($order);
        $this->orders[$hash] = $order;
        return $order;
    }

    public function addItem(Order $order, Item $item)
    {
        $hash = spl_object_hash($order);
        $this->orders[$hash]->addItem($item);
        return $this;
    }
}
