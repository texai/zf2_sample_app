<?php

namespace Sales;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

use DateTime;
use Customer\Customer;
use Customer\Address;

/**
 *
 */
class Order implements EventManagerAwareInterface
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

    private $eventManager;

    /**
     *
     */
    public function __construct(array $options = array())
    {
        $this->number         = (isset($options['number'])) ? $options['number'] : time();
        $this->customer       = (isset($options['customer'])) ? $options['customer'] : null;
        $this->date           = (isset($options['date'])) ? $options['date'] : new DateTime();
        $this->status         = (isset($options['status'])) ? $options['status'] : self::PENDING_STATUS;
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
        return $this->total;
    }

    public function getItems()
    {
        return $items;
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

    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function setCustomer(Customer $customer)
    {
        $params   = compact('customer');
        $response = $this->getEventManager()
                         ->trigger(__FUNCTION__, $this, $params);
        if ($response->stopped()) {
            throw new \Exception("Billing address is mandatory");
        }
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
        $params = compact('items');
        $this->getEventManager()
             ->trigger(__FUNCTION__, $this, $params);
        $this->items = $items;
        return $this;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function addItem(Item $item)
    {
        $params = compact('item');
        $this->getEventManager()
             ->trigger(__FUNCTION__ . '.pre', $this, $params);
        $counter = count($this->items);
        $item->setNumber($counter + 1);
        $this->items[$counter] = $item;
        $this->getEventManager()
             ->trigger(__FUNCTION__ . '.post', $this, $params);
        return $this;
    }
}
