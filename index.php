<?php
date_default_timezone_set('America/Lima');

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Customer\Address;
use Customer\Customer;
use Product\Category;
use Product\Product;
use Sales\Sales;
use Sales\Order;
use Sales\Item;

$loader = include 'vendor/autoload.php';
$loader->add('Zend', 'vendor/zendframework/zend-servicemanager/');
$loader->add('Customer', './' );
$loader->add('Product', './' );
$loader->add('Sales', './' );

$sm_config = array(
    'invokables' => array(
        'LondonWarehouse'  => 'Product\Warehouse',
        'LimaWarehouse'    => 'Product\Warehouse',
        'NewYorkWarehouse' => 'Product\Warehouse',
    ),
    'aliases' => array (
        'MainWarehouse'    => 'LondonWarehouse',
    ),
    'initializers' => array(
        'Category' => 'Product\CategoryInitializer',
        'Warehouse' => 'Product\WarehouseInitializer',
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
            ));
            $book->setMainCategory($serviceManager->get('BooksCategory'));
            return $book;
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
$order1 = $sales->createOrder(array(
    'number'   => '94KEI1938300Z1',
    'customer' => $rasmus,
));
$order2 = $sales->createOrder(array('customer' => $yukihiro));
$order3 = $sales->createOrder(array('customer' => $yukihiro));
$item = new Item(array(
    'product'  => $sm->get('Product'),
    'quantity' => 3,
    'discount_percentage' => 0.00,
    'price' => 2000
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
    'discount_percentage' => 0.00,
    'price' => 1500
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 1,
    'discount_percentage' => 0.00,
    'price' => 700
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 1,
    'discount_percentage' => 0.00,
    'price' => 4000
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 7,
    'discount_percentage' => 3.00,
    'price' => 2013
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 7,
    'discount_percentage' => 3.00,
    'price' => 2013
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 9,
    'discount_percentage' => 13.00,
    'price' => 2599
));
$sales->addItem($order3, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
    'discount_percentage' => 0.00,
    'price' => 700
));
$sales->addItem($order2, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
    'discount_percentage' => 0.00,
    'price' => 4000
));
$sales->addItem($order3, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 1,
    'discount_percentage' => 0.00,
    'price' => 5989
));
$sales->addItem($order1, $item);
$item = new Item(array(
    'product' => $sm->get('Product'),
    'quantity' => 2,
    'discount_percentage' => 7.00,
    'price' => 5689
));
$sales->addItem($order3, $item);
$orders = compact('order1', 'order2', 'order3');
echo '<h2>AVAILABLE ORDERS</h2>' . PHP_EOL;
echo '<pre>';
var_dump($orders);
echo '</pre>';
