<?php
use Magento\Framework\App\Bootstrap;

require '/home/karanaggarwal166/Downloads/magento2/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$om = $bootstrap->getObjectManager();

$jobRepository = $om->get(\Akeneo\Connector\Model\JobRepository::class);
$jobExecutor   = $om->get(\Akeneo\Connector\Executor\JobExecutor::class);

$job = $jobRepository->getByCode('product');

$ref = new \ReflectionClass($jobExecutor);
$prop = $ref->getProperty('currentJob');
$prop->setAccessible(true);
$prop->setValue($jobExecutor, $job);

$prop2 = $ref->getProperty('currentJobClass');
$prop2->setAccessible(true);
$productJob = $om->get(\Akeneo\Connector\Job\Product::class);
$productJob->setFamily('clothing');
$prop2->setValue($jobExecutor, $productJob);

$productJob->setJobExecutor($jobExecutor);

$steps = [
    'resetUuid',
    'createTable',
    'insertData',
    'addRequiredData',
    'checkEntities',
    'matchEntities',
    'createEntities',
    'setValues',
    'setCategories',
    'dropTable'
];

foreach ($steps as $step) {
    echo "Running step: $step...\n";
    try {
        $productJob->$step();
        echo "Completed: $step\n";
    } catch (\Throwable $e) {
        echo "Error in $step: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
    }
}

echo "DIRECT PRODUCT IMPORT COMPLETED!\n";
