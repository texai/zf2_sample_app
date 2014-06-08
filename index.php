<?php
date_default_timezone_set('America/Lima');

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Zend\EventManager\EventManager;
use Zend\EventManager\ResponseCollection;
use Customer\Customer;
use Product\Product;
use Product\Warehouse;
use Sales\Sales;
use Sales\Item;
use Sales\ListenerAggregate\OrderListener;
use Geo\Country;

$loader = include 'vendor/autoload.php';
$loader->add('Zend', 'vendor/zendframework/zend-servicemanager/');
$loader->add('Zend', 'vendor/zendframework/zend-eventmanager/');
$loader->add('Customer', './' );
$loader->add('Product', './' );
$loader->add('Sales', './' );
$loader->add('Geo', './' );

$sm_config = array(
    'invokables' => array(
        'SharedEventManager' => 'Zend\EventManager\SharedEventManager',
    ),
    'aliases' => array (
        'MainWarehouse' => 'LondonWarehouse',
    ),
    'initializers' => array(
        'CategoryInitializer'  => 'Product\Initializer\CategoryInitializer',
        'WarehouseInitializer' => 'Product\Initializer\WarehouseInitializer',
    ),
    'factories' => array(
        'Address'         => 'Customer\Factory\AddressFactory',
        'Product'         => 'Product\Factory\ProductFactory',
        'BooksCategory'   => 'Product\Factory\BooksCategoryFactory',
        'MusicCategory'   => 'Product\Factory\MusicCategoryFactory',
        'VideoCategory'   => 'Product\Factory\VideoCategoryFactory',
        'LondonWarehouse' => function ($serviceManager) {
            $country   = new Country('United Kingdom', 'UK');
            $warehouse = new Warehouse($country);
            return $warehouse;
        },
        'LimaWarehouse' => function ($serviceManager) {
            $country   = new Country('Peru', 'PE');
            $warehouse = new Warehouse($country);
            return $warehouse;
        },
        'NewYorkWarehouse' => function ($serviceManager) {
            $country   = new Country('United States', 'US');
            $warehouse = new Warehouse($country);
            return $warehouse;
        },
        'Gift'             => function ($serviceManager) {
            $book = new Product(array(
                'code'        => '9781449392772',
                'name'        => 'Programming PHP',
                'description' => 'Creating Dynamic Pages',
                'price'       => 0.00
            ));
            $book->setMainCategory($serviceManager->get('BooksCategory'));
            return $book;
        },
        'EventManager'     => function ($serviceManager) {
            $eventManager       = new EventManager();
            $sharedEventManager = $serviceManager->get('SharedEventManager');
            $orderListener      = new OrderListener();
            $sharedEventManager->attachAggregate($orderListener);
            $sharedEventManager->attach('Sales\Sales', 'addItem', function ($event) use ($serviceManager) {
                $sales         = $event->getTarget();
                $order         = $event->getParam('order');
                $item          = $event->getParam('item');
                $countryCode   = $order->getBillingAddress()
                                       ->getCountry()
                                       ->getCode();
                $product       = $item->getProduct();
                $mainWarehouse = $serviceManager->get('MainWarehouse');
                if (isset($sales->warehouses[$countryCode])) {
                    $warehouse = $sales->warehouses[$countryCode];
                    if (!$warehouse->isInStock($product, $item->getQuantity())) {
                        $warehouse = $mainWarehouse;
                    }
                } else {
                    $warehouse = $mainWarehouse;
                }
                if (!$warehouse->isInStock($product, $item->getQuantity())) {
                    $otherWarehouses = array_diff_key(
                        $sales->getWarehouses(),
                        array($warehouse->getCountry()
                                        ->getCode() => $warehouse)
                    );
                    $outOfStock = true;
                    foreach ($otherWarehouses as $otherWarehouse) {
                        if ($otherWarehouse->isInStock($product, $item->getQuantity())) {
                            $warehouse  = $otherWarehouse;
                            $outOfStock = false;
                            break;
                        }
                    }
                    if ($outOfStock) {
                        $event->stopPropagation(true);
                        $response = new ResponseCollection();
                        $response->setStopped(true);
                        return $response;
                    }
                }
                $warehouse->dispatch(
                    $product,
                    $item->getQuantity()
                );
            });
            $eventManager->setSharedManager($sharedEventManager);
            return $eventManager;
        },
    ),
    'abstract_factories' => array(
        'Product\Factory\WarehouseAbstractFactory',
    ),
    'shared' => array(
        'Gift'    => false,
        'Address' => false,
        'Product' => false,
    ),
);

$app_config = array(
    'categories' => array(
        'discount_policies' => array (
            'CTBUK' => array(
                'Mon' =>  7.00,
                'Wed' => 15.00,
                'Fri' => 30.00,
                'Sun' => 50.00,
            ),
            'CTMUK' => array(
                'Sat' => 40.00,
                'Sun' => 30.00,
            ),
            'CTVUK' => array(
                'Tue' => 10.00,
                'Wed' => 10.00,
                'Thu' => 13.00,
            ),
        ),
    ),
);

$sm = new ServiceManager(new Config($sm_config));
$sm->setService('Config', $app_config);
$warehouses = array(
    'ENG' => $sm->get('LondonWarehouse'),
    'PE'  => $sm->get('LimaWarehouse'),
    'DE'  => $sm->get('BerlinWarehouse'),
    'US'  => $sm->get('NewYorkWarehouse'),
);
echo '<h2>ALWAYS A NEW GIFT INSTANCE</h2>';
$gift1 = $sm->get('Gift');
$gift2 = $sm->get('Gift');
echo '<pre>';
var_dump(spl_object_hash($gift1));
echo '</pre>';
echo '<pre>';
var_dump(spl_object_hash($gift2));
echo '</pre>';

echo '<h2>AVAILABLE WAREHOUSES</h2>' . PHP_EOL;
echo '<pre>';
var_dump($warehouses);
echo '</pre>';

$address1 = $sm->get('Address');
$address2 = $sm->get('Address');
$address3 = $sm->get('Address');
$address4 = $sm->get('Address');
$address5 = $sm->get('Address');
$addresses = compact('address1', 'address2', 'address3', 'address4', 'address5');
echo '<h2>AVAILABLE ADDRESSES</h2>' . PHP_EOL;
echo '<pre>';
var_dump($addresses);
echo '</pre>';

$rasmus = new Customer(array(
    'first_name' => 'Rasmus',
    'surname'    => 'Lerdorf',
    'email'      => 'rasmus.lerdorf@php.net',
    'telephone'  => '44-7981-383847'
));
$rasmus->setAddresses(array(
    'billing' => $address1,
    'contact' => [$address2, $address5, $address3]
));
$yukihiro = new Customer(array(
    'first_name' => 'Yukihiro',
    'surname'    => 'Matsumoto',
    'email'      => 'yukihiro.matsumoto@ruby.org',
    'telephone'  => '23-9980-385877'
));
$yukihiro->setAddresses(array(
    'billing' => $address3,
    'contact' => [$address2, $address4])
);

echo '<h2>CUSTOMERS</h2>' . PHP_EOL;
echo '<pre>';
var_dump($rasmus);
echo '</pre>';
echo '<pre>';
var_dump($yukihiro);
echo '</pre>';

$products = array();
for ($i = 0; $i < 20; $i++) {
    $product        = $sm->get('Product');
    $key            = $product->getCode();
    $products[$key] = $product;
}
echo '<h2>AVAILABLE PRODUCTS</h2>' . PHP_EOL;
echo '<pre>';
var_dump($products);
echo '</pre>';

$sales = new Sales();
$sales->setWarehouses($warehouses);
$order1 = $sales->createOrder(array('number'   => '94KEI1938300Z1',));
$order1->getEventManager()->setSharedManager($sm->get('EventManager')->getSharedManager());
$order1->setCustomer($rasmus);
$order2 = $sales->createOrder();
$order2->getEventManager()->setSharedManager($sm->get('EventManager')->getSharedManager());
$order2->setCustomer($yukihiro);
$order3 = $sales->createOrder();
$order3->getEventManager()->setSharedManager($sm->get('EventManager')->getSharedManager());
$order3->setCustomer($yukihiro);
$item = new Item(array(
    'product'  => $sm->get('Product'),
    'quantity' => 10,
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 1,
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 1,
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 7,
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 7,
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 9,
));
$sales->addItem($order3, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
));
$sales->addItem($order3, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 1,
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
));
$sales->addItem($order3, $item);
$orders = compact('order1', 'order2', 'order3');
echo '<h2>AVAILABLE ORDERS</h2>' . PHP_EOL;
echo '<pre>';
var_dump($orders);
echo '</pre>';
