<?php

$loader = include 'vendor/autoload.php';
$loader->add('Zend', 'vendor/zendframework/zend-servicemanager/');

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config;

$sm = new ServiceManager(new Config(array()));


