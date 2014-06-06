<?php
date_default_timezone_set('America/Lima');

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;
use Customer\Address;
use Customer\Customer;
use Product\Category;
use Product\Product;
use Sales\Order;
use Sales\Item;

$loader = include 'vendor/autoload.php';
$loader->add('Zend', 'vendor/zendframework/zend-servicemanager/');
$loader->add('Customer', './' );
$loader->add('Product', './' );
$loader->add('Sales', './' );

$address1 = new Address(array(
    'number'  => 145,
    'street'  => 'Featherstone Street',
    'city'    => 'London',
    'country' => 'England'
));
$address2 = new Address(array(
    'number'  => 61,
    'street'  => 'Wellfield Road',
    'city'    => 'Cardiff',
    'country' => 'Wales'
));
$address3 = new Address(array(
    'number'  => 41,
    'street'  => 'George Street',
    'city'    => 'London',
    'country' => 'England'
));
$address4 = new Address(array(
    'number'  => 46,
    'street'  => 'Morningside Road',
    'city'    => 'Edinburgh',
    'country' => 'Scotland'
));
$address5 = new Address(array(
    'number'  => 27,
    'street'  => 'Colmore Road',
    'city'    => 'Birmingham',
    'country' => 'England'
));
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

$booksCategory = new Category(array(
    'code'        => 'CTBUK',
    'name'        => 'Books',
    'description' => 'Books, Calendars, Card Decks, Sheet Music, Magazine, Journals'
));
$musicCategory = new Category(array(
    'code'        => 'CTMUK',
    'name'        => 'Music',
    'description' => 'CDs, Vinyl, and other sound recordings'
));
$moviesCategory = new Category(array(
    'code'        => 'CTOUK',
    'name'        => 'Movies',
    'description' => 'Movies, TV'
));
$categories = compact('booksCategory', 'musicCategory', 'moviesCategory');
echo '<h2>AVAILABLE CATEGORIES</h2>' . PHP_EOL;
echo '<pre>';
var_dump($categories);
echo '</pre>';

$pythonBook = new Product(array(
    'code'        => '9781449355739',
    'name'        => 'Learning Python, 5th Edition',
    'description' => 'Powerful Object-Oriented Programming'
));
$pythonBook->setMainCategory($booksCategory);
$phpBook = new Product(array(
    'code'        => '9781449392772',
    'name'        => 'Programming PHP',
    'description' => 'Creating Dynamic Pages',
));
$phpBook->setMainCategory($booksCategory);
$rubyBook = new Product(array(
    'code'        => '9780596516178',
    'name'        => 'The Ruby Programming Language',
    'description' => '',
));
$rubyBook->setMainCategory($booksCategory);

$jazzAlbum = new Product(array(
    'code'        => 'B0030GBSVG',
    'name'        => 'Strangers In The Night',
    'description' => 'The very best of Frank Sinatra'
));
$jazzAlbum->setMainCategory($musicCategory);
$rockAlbum = new Product(array(
    'code'        => 'B000002GYN',
    'name'        => 'Eagles',
    'description' => 'The very best of Eagles'
));
$rockAlbum->setMainCategory($musicCategory);
$grungeAlbum = new Product(array(
    'code'        => 'B000003TA4',
    'name'        => 'Nevermind',
    'description' => 'Nirvana Nevermind'
));
$grungeAlbum->setMainCategory($musicCategory);

$sherlockTVShow = new Product(array(
    'code'        => 'B00E3UN44W',
    'name'        => 'Sherlock',
    'description' => 'Modern version of one of the most greatest detectives of al times'
));
$sherlockTVShow->setMainCategory($moviesCategory);
$lutherTVShow = new Product(array(
    'code'        => 'B0041QSZFG',
    'name'        => 'Luther',
    'description' => ''
));
$lutherTVShow->setMainCategory($moviesCategory);
$blackMirrorTVShow = new Product(array(
    'code'        => 'B008M0P9I8',
    'name'        => 'Black Mirror',
    'description' => ''
));
$blackMirrorTVShow->setMainCategory($moviesCategory);
$products = compact(
    'pythonBook',
    'phpBook',
    'rubyBook',
    'jazzAlbum',
    'rockAlbum',
    'grungeAlbum',
    'sherlockTVShow',
    'lutherTVShow',
    'blackMirrorTVShow'
);
echo '<h2>AVAILABLE PRODUCTS</h2>' . PHP_EOL;
echo '<pre>';
var_dump($products);
echo '</pre>';

$order1 = new Order(array(
    'number'   => '94KEI1938300Z1',
    'customer' => $rasmus,
));
$item0101 = new Item(array(
    'product' => $rubyBook,
    'quantity' => 3,
    'discount_percentage' => 0.00,
    'price' => 2000
));
$order1->addItem($item0101);
$item0102 = new Item(array(
    'product' => $pythonBook,
    'quantity' => 2,
    'discount_percentage' => 0.00,
    'price' => 1500
));
$order1->addItem($item0102);
$item0103 = new Item(array(
    'product' => $jazzAlbum,
    'quantity' => 1,
    'discount_percentage' => 0.00,
    'price' => 700
));
$order1->addItem($item0103);
$item0104 = new Item(array(
    'product' => $sherlockTVShow,
    'quantity' => 1,
    'discount_percentage' => 0.00,
    'price' => 4000
));
$order1->addItem($item0104);

$order2 = new Order(array('customer' => $yukihiro));
$item0201 = new Item(array(
    'product' => $phpBook,
    'quantity' => 7,
    'discount_percentage' => 3.00,
    'price' => 2013
));
$order2->addItem($item0201);
$item0202 = new Item(array(
    'product' => $pythonBook,
    'quantity' => 9,
    'discount_percentage' => 13.00,
    'price' => 2599
));
$order2->addItem($item0202);
$item0203 = new Item(array(
    'product' => $jazzAlbum,
    'quantity' => 2,
    'discount_percentage' => 0.00,
    'price' => 700
));
$order2->addItem($item0203);
$item0204 = new Item(array(
    'product' => $sherlockTVShow,
    'quantity' => 2,
    'discount_percentage' => 0.00,
    'price' => 4000
));
$order2->addItem($item0204);

$order3 = new Order(array('customer' => $yukihiro));
$item0301 = new Item(array(
    'product' => $rockAlbum,
    'quantity' => 1,
    'discount_percentage' => 0.00,
    'price' => 5989
));
$order3->addItem($item0301);
$item0302 = new Item(array(
    'product' => $lutherTVShow,
    'quantity' => 2,
    'discount_percentage' => 7.00,
    'price' => 5689
));
$order3->addItem($item0302);
$orders = compact('order1', 'order2', 'order3');
echo '<h2>AVAILABLE ORDERS</h2>' . PHP_EOL;
echo '<pre>';
var_dump($orders);
echo '</pre>';
//$sm = new ServiceManager(new Config(array()));
