<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$executor = $om->get(\Akeneo\Connector\Executor\JobExecutor::class);

echo "Executing Akeneo Product import via JobExecutor...\n";
$executor->execute('product');

echo "FINISHED AKENEO PRODUCT IMPORT!\n";
