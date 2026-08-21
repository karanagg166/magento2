<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$authenticator = $om->get(\Akeneo\Connector\Helper\Authenticator::class);
$client = $authenticator->getAkeneoApiClient();

if (!$client) {
    die("ERROR: Could not create Akeneo client from Magento configuration.\n");
}

echo "SUCCESS: Akeneo Client created successfully!\n";

try {
    $categories = $client->getCategoryApi()->all(10);
    echo "Categories fetched from Akeneo:\n";
    foreach ($categories as $cat) {
        echo " - Code: " . $cat['code'] . " | Parent: " . ($cat['parent'] ?? 'null') . "\n";
    }
} catch (\Throwable $e) {
    echo "Error fetching categories: " . $e->getMessage() . "\n";
}

try {
    $products = $client->getProductApi()->all(10);
    echo "Products fetched from Akeneo:\n";
    foreach ($products as $prod) {
        echo " - SKU: " . $prod['identifier'] . " | Family: " . ($prod['family'] ?? 'null') . "\n";
    }
} catch (\Throwable $e) {
    echo "Error fetching products: " . $e->getMessage() . "\n";
}
