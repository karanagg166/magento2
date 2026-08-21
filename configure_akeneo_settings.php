<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$configWriter = $om->get(\Magento\Framework\App\Config\Storage\WriterInterface::class);
$cacheTypeList = $om->get(\Magento\Framework\App\Cache\TypeListInterface::class);

$configWriter->save('akeneo_connector/product/attribute_mapping', '[]');

$cacheTypeList->cleanType('config');
echo "Saved akeneo_connector/product/attribute_mapping = []!\n";
