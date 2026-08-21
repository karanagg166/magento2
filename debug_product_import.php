<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$authenticator = $om->get(\Akeneo\Connector\Helper\Authenticator::class);
$productJob    = $om->get(\Akeneo\Connector\Job\Product::class);

$productJob->setAkeneoClient($authenticator->getAkeneoApiClient());
$productJob->setFamily('clothing');

echo "Calling createTable()...\n";
$productJob->createTable();

echo "Calling fillTable()...\n";
$productJob->fillTable();

echo "DEBUG FINISHED!\n";
