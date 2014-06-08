<?php
date_default_timezone_set('America/Lima');

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Zend\EventManager\EventManager;
use Zend\EventManager\ResponseCollection;
use Customer\Customer;
use Product\Product;
use Sales\Sales;
use Sales\Item;

$loader = include 'vendor/autoload.php';
$loader->add('Zend', 'vendor/zendframework/zend-servicemanager/');
$loader->add('Zend', 'vendor/zendframework/zend-eventmanager/');
$loader->add('Customer', './' );
$loader->add('Product', './' );
$loader->add('Sales', './' );

$sm_config = array(
    'invokables' => array(
        'LondonWarehouse'    => 'Product\Warehouse',
        'LimaWarehouse'      => 'Product\Warehouse',
        'NewYorkWarehouse'   => 'Product\Warehouse',
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
        'Address'       => 'Customer\Factory\AddressFactory',
        'Product'       => 'Product\Factory\ProductFactory',
        'BooksCategory' => 'Product\Factory\BooksCategoryFactory',
        'MusicCategory' => 'Product\Factory\MusicCategoryFactory',
        'VideoCategory' => 'Product\Factory\VideoCategoryFactory',
        'Gift'          => function ($serviceManager) {
            $book = new Product(array(
                'code'        => '9781449392772',
                'name'        => 'Programming PHP',
                'description' => 'Creating Dynamic Pages',
                'price'       => 0.00
            ));
            $book->setMainCategory($serviceManager->get('BooksCategory'));
            return $book;
        },
        'EventManager' => function ($serviceManager) {
            $eventManager       = new EventManager();
            $sharedEventManager = $serviceManager->get('SharedEventManager');
            $sharedEventManager->attach('Sales\Order', 'setItems', function ($event) {
                $order = $event->getTarget();
                $items = $event->getParam('items');
                $total = 0.00;
                foreach ($items as $item) {
                    $total += $item->getTotal();
                }
                $order->setTotal($total);
            });
            $sharedEventManager->attach('Sales\Order', 'addItem.pre', function ($event) {
                $order   = $event->getTarget();
                $item    = $event->getParam('item');
                $product = $item->getProduct();
                $item->setPrice($product->getPrice());
                if ($product->hasDiscount()) {
                   $item->setDiscountPercentage($product->getDiscountPercentage());
                }
            }, 50);
            $sharedEventManager->attach('Sales\Order', 'addItem.post', function ($event) {
                $order = $event->getTarget();
                $item  = $event->getParam('item');
                $total = $order->getTotal();
                $total += $item->getTotal();
                $order->setTotal($total);
            }, -50);
            $sharedEventManager->attach('Sales\Order', 'setCustomer', function ($event) {
                $order    = $event->getTarget();
                $customer = $event->getParam('customer');
                if (!$customer->hasBillingAddress()) {
                    $event->stopPropagation(true);
                    $response = new ResponseCollection();
                    $response->setStopped(true);
                    return $response;
                }
                $order->setBillingAddress($customer->getBillingAddress());
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
$lnWarehouse = $sm->get('LondonWarehouse');
$liWarehouse = $sm->get('LimaWarehouse');
$nyWarehouse = $sm->get('NewYorkWarehouse');
$blWarehouse = $sm->get('BerlinWarehouse');
$warehouse   = $sm->get('MainWarehouse');
$warehouses = compact('lnWarehouse', 'liWarehouse', 'nyWarehouse', 'warehouse', 'blwarehouse');
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
    'quantity' => 3,
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
