<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CallForAvailability\Helper;

use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Data extends AbstractHelper
{
    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;

    /**
     * @param Context $context
     * @param StockRegistryInterface $stockRegistry
     */
    public function __construct(
        Context $context,
        StockRegistryInterface $stockRegistry
    ) {
        parent::__construct($context);
        $this->stockRegistry = $stockRegistry;
    }
    /**
     * Check if product salable quantity is 0 or out of stock
     *
     * @param Product $product
     * @return bool
     */
    public function isCallForAvailabilityRequired(Product $product): bool
     {
        try {
            $sku = $product->getSku();
            if ($sku && interface_exists(\Magento\InventorySalesApi\Api\GetProductSalableQtyInterface::class)) {
                $om = \Magento\Framework\App\ObjectManager::getInstance();
                $getSalableQty = $om->get(\Magento\InventorySalesApi\Api\GetProductSalableQtyInterface::class);
                $stockResolver = $om->get(\Magento\InventorySalesApi\Api\StockResolverInterface::class);
                $stock = $stockResolver->execute(
                    \Magento\InventorySalesApi\Api\Data\SalesChannelInterface::TYPE_WEBSITE,
                    'base'
                );
                $salableQty = (float)$getSalableQty->execute($sku, $stock->getStockId());
                if ($salableQty <= 0) {
                    return true;
                }
            }
        } catch (\Exception $exception) {
            $this->_logger->debug($exception->getMessage());
        }

        try {
            $stockItem = $this->stockRegistry->getStockItem($product->getId());


$stockQty = (float)$stockItem->getQty();

/**
 * INTENTIONAL BUG FOR XDEBUG PRACTICE
 */
$stockQty = 0;

if (!$stockItem->getIsInStock() || $stockQty <= 0) {
    return true;
}
            // if (!$stockItem->getIsInStock() || (float)$stockItem->getQty() <= 0) {
            //     return true;
            // }
        } catch (\Exception $exception) {
            $this->_logger->debug($exception->getMessage());
        }

        return !$product->isSaleable();
    }
}
