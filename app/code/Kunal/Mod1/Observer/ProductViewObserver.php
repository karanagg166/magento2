<?php

namespace Kunal\Mod1\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class ProductViewObserver implements ObserverInterface
{
    protected LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
        if ($product) {$loggerMessage = sprintf(
            "Viewed Product: %s | ID: %d | SKU: %s | Type: %s | QTY: %s | Price: %s",
            $product->getName(),
            $product->getId(),
            $product->getSku(),
            $product->getTypeId(),
            $product->getQty(),
            $product->getPrice()
        );

        $this->logger->info($loggerMessage);
        }
    }
}
