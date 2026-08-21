<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$configWriter = $om->get(\Magento\Framework\App\Config\Storage\WriterInterface::class);
$encryptor    = $om->get(\Magento\Framework\Encryption\EncryptorInterface::class);
$cacheTypeList = $om->get(\Magento\Framework\App\Cache\TypeListInterface::class);

$encryptedPassword     = $encryptor->encrypt('admin123');
$encryptedClientSecret = $encryptor->encrypt('b4f0a554c8e4d910e9f91e0bfb016003809b462281471a84fc2be7f6f50d1c58');

$configs = [
    'akeneo_connector/akeneo_api/base_url' => 'http://127.0.0.1:8090',
    'akeneo_connector/akeneo_api/client_id' => '1_9b2f7c47fbd41d15a2507716d012690d',
    'akeneo_connector/akeneo_api/client_secret' => $encryptedClientSecret,
    'akeneo_connector/akeneo_api/username' => 'admin',
    'akeneo_connector/akeneo_api/password' => $encryptedPassword,
    'akeneo_connector/category/root_category' => 'master',
    'akeneo_connector/product/channel' => 'ecommerce',
    'akeneo_connector/product/locale' => 'en_US',
];

foreach ($configs as $path => $value) {
    $configWriter->save($path, $value);
}

$cacheTypeList->cleanType('config');
echo "Akeneo encrypted credentials updated in Magento!\n";
