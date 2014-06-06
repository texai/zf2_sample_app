<?php

namespace Sales;

use Product\Product;

/**
 *
 */
class Item
{
    private $number;

    private $product;

    private $quantity;

    private $discount;

    private $price;

    /**
     *
     */
    public function __construct(array $options = array())
    {
        $this->number   = (isset($options['number'])) ? $options['number'] : 0;
        $this->product  = (isset($options['product'])) ? $options['product'] : null;
        $this->quantity = (isset($options['quantity'])) ? $options['quantity'] : 0;
        $this->discount = (isset($options['discount_percentage'])) ? $options['discount_percentage'] : 0.00;
        $this->price    = (isset($options['price'])) ? $options['price'] : 0;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getDiscountPercentage()
    {
        return $this->discount;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getTotal()
    {
        return $this->quantity * $this->price * (1 -  $this->discount);
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setDiscountPercentage($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
}
