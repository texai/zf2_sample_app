<?php

namespace Sales;

use DateTime;
use Customer\Customer;
use Customer\Address;

/**
 *
 */
class Order
{
    const PENDING_STATUS = 'pending';

    const PROCESSED_STATUS = 'processed';

    const CANCELLED_STATUS = 'cancelled';

    private $number;

    private $customer;

    private $date;

    private $status;

    private $billingAddress;

    private $total;

    private $items;

    /**
     *
     */
    public function __construct(array $options = array())
    {
        $this->number         = (isset($options['number'])) ? $options['number'] : time();
        $this->customer       = (isset($options['customer'])) ? $options['customer'] : null;
        $this->date           = (isset($options['date'])) ? $options['date'] : new DateTime();
        $this->status         = (isset($options['status'])) ? $options['status'] : PENDING_STATUS;
        $this->billingAddress = (isset($options['billing_address'])) ? $options['billing_address'] : null;
        $this->total          = 0.00;
        $this->items          = array();
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function getTotal()
    {
        return $total;
    }

    public function getItems()
    {
        return $items;
    }

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setBillingAddress(Address $address)
    {
        $this->billingAddress = $address;
        return $this;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    public function addItem(Item $item)
    {
        $counter = count($this->items);
        $item->setNumber($counter + 1);
        $this->items[$counter] = $item;
        return $this;
    }
}
