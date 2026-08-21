<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$job = $om->get(\Akeneo\Connector\Job\Category::class);
$jobExecutor = $om->get(\Akeneo\Connector\Executor\JobExecutor::class);

echo "Starting category import with logger output...\n";
$jobExecutor->execute('category');

echo "\nFinished execution.\n";
