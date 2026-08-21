<?php

namespace Karan\LowStockNotification\Observer;

use Karan\LowStockNotification\Logger\Logger;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventoryCatalogApi\Api\DefaultStockProviderInterface;
use Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\InventorySalesApi\Api\StockResolverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Dispatches the custom "karan_product_qty_below_threshold" event whenever a
 * product's salable quantity falls below the configured threshold.
 *
 * Two triggers feed this observer:
 *  - cataloginventory_stock_item_save_after    admin qty edits, imports
 *  - sales_model_service_quote_submit_success  order placement (frontend + admin)
 *
 * The order trigger is required because MSI never saves the stock item during
 * checkout — it only appends a row to inventory_reservation — so the stock-item
 * event alone can never observe an order. Magento\Quote\Model\QuoteManagement
 * dispatches submit_success immediately after OrderManagementInterface::place(),
 * which is where MSI's AppendReservationsAfterOrderPlacementPlugin adds the
 * reservation, so the qty read here already accounts for the order just placed.
 *
 * Quantities always come from GetProductSalableQtyInterface, never from
 * StockItemInterface::getQty(): the latter is the physical legacy qty and ignores
 * reservations, which makes it the wrong number in any MSI store.
 */
class StockItemSaveObserver implements ObserverInterface
{
    public const XML_PATH_ENABLED   = 'lowstock/general/enabled';
    public const XML_PATH_THRESHOLD = 'lowstock/general/threshold';

    public const CUSTOM_EVENT = 'karan_product_qty_below_threshold';

    private ScopeConfigInterface $scopeConfig;
    private EventManager $eventManager;
    private ProductRepositoryInterface $productRepository;
    private Logger $logger;
    private GetProductSalableQtyInterface $getProductSalableQty;
    private StockResolverInterface $stockResolver;
    private DefaultStockProviderInterface $defaultStockProvider;
    private IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowed;
    private StoreManagerInterface $storeManager;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EventManager $eventManager,
        ProductRepositoryInterface $productRepository,
        Logger $logger,
        GetProductSalableQtyInterface $getProductSalableQty,
        StockResolverInterface $stockResolver,
        DefaultStockProviderInterface $defaultStockProvider,
        IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowed,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->eventManager = $eventManager;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->getProductSalableQty = $getProductSalableQty;
        $this->stockResolver = $stockResolver;
        $this->defaultStockProvider = $defaultStockProvider;
        $this->isSourceItemManagementAllowed = $isSourceItemManagementAllowed;
        $this->storeManager = $storeManager;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getData('order');
        if ($order instanceof OrderInterface) {
            $this->checkOrder($order);
            return;
        }

        $stockItem = $observer->getEvent()->getData('item');
        if ($stockItem instanceof StockItemInterface) {
            $this->checkStockItem($stockItem);
        }
    }

    /**
     * Admin qty edit / import path.
     */
    private function checkStockItem(StockItemInterface $stockItem): void
    {
        if (!$stockItem->getProductId()) {
            return;
        }

        $storeId = $stockItem->getStoreId() ?  (int) $stockItem->getStoreId() : null;
        if (!$this->isEnabled($storeId)) {
            return;
        }

        $threshold = $this->getThreshold($storeId);
        if ($threshold <= 0) {
            return;
        }

        // Only meaningful when Magento actually manages stock for this item.
        if (method_exists($stockItem, 'getManageStock') && !$stockItem->getManageStock()) {
            return;
        }

        $product = $this->loadProduct((int) $stockItem->getProductId(), $storeId);
        if (!$product) {
            return;
        }

        // A stock-item save carries no sales-channel context, so evaluate the default stock.
        $this->notifyIfBelow(
            $product,
            $this->defaultStockProvider->getId(),
            $threshold,
            ['stock_item' => $stockItem]
        );
    }

    /**
     * Order placement path: reservations are already in place by the time this runs.
     */
    private function checkOrder(OrderInterface $order): void
    {
        $storeId = (int) $order->getStoreId();
        if (!$this->isEnabled($storeId)) {
            return;
        }

        $threshold = $this->getThreshold($storeId);
        if ($threshold <= 0) {
            return;
        }

        $stockId = $this->resolveStockId($storeId);
        if ($stockId === null) {
            return;
        }

        $seen = [];
        foreach ((array) $order->getItems() as $item) {
            $sku = (string) $item->getSku();
            if ($sku === '' || isset($seen[$sku])) {
                continue;
            }
            $seen[$sku] = true;

            // Configurable/bundle/grouped parents own no source items; their simple
            // children are separate order items and get checked on their own.
            if (!$this->isSourceItemManagementAllowed->execute((string) $item->getProductType())) {
                continue;
            }

            $product = $this->loadProduct((int) $item->getProductId(), $storeId);
            if ($product) {
                $this->notifyIfBelow($product, $stockId, $threshold, ['order' => $order]);
            }
        }
    }

    /**
     * Resolve the stock serving the store's website.
     */
    private function resolveStockId(int $storeId): ?int
    {
        try {
            $websiteCode = $this->storeManager->getStore($storeId)->getWebsite()->getCode();

            return (int) $this->stockResolver
                ->execute(SalesChannelInterface::TYPE_WEBSITE, $websiteCode)
                ->getStockId();
        } catch (\Throwable $e) {
            $this->logger->warning(sprintf(
                'Low-stock check skipped: could not resolve stock for store %d: %s',
                $storeId,
                $e->getMessage()
            ));

            return null;
        }
    }

    /**
     * Compare salable qty against the threshold and fire the custom event when below.
     */
    private function notifyIfBelow(
        ProductInterface $product,
        int $stockId,
        float $threshold,
        array $extra = []
    ): void {
        $qty = $this->getSalableQty((string) $product->getSku(), $stockId);
        if ($qty === null || $qty >= $threshold) {
            return;
        }

        $this->logger->info(sprintf(
            'Low stock detected for "%s" (SKU: %s, ID: %d): salable qty %s is below threshold %s. Dispatching %s.',
            $product->getName(),
            $product->getSku(),
            $product->getId(),
            $qty,
            $threshold,
            self::CUSTOM_EVENT
        ));

        $this->eventManager->dispatch(self::CUSTOM_EVENT, $extra + [
            'product'   => $product,
            'qty'       => $qty,
            'threshold' => $threshold,
        ]);
    }

    /**
     * Salable qty = source qty minus reservations. Null when it cannot be determined.
     */
    private function getSalableQty(string $sku, int $stockId): ?float
    {
        try {
            return (float) $this->getProductSalableQty->execute($sku, $stockId);
        } catch (LocalizedException $e) {
            // Raised for product types without source-item support and for SKUs
            // missing from the stock index.
            $this->logger->warning(
                sprintf('Low-stock check skipped for SKU %s: %s', $sku, $e->getMessage())
            );

            return null;
        }
    }

    private function loadProduct(int $productId, ?int $storeId): ?ProductInterface
    {
        try {
            return $this->productRepository->getById($productId, false, $storeId);
        } catch (NoSuchEntityException $e) {
            $this->logger->warning(
                sprintf('Low-stock check skipped: product ID %d not found.', $productId)
            );

            return null;
        }
    }

    private function isEnabled(?int $storeId): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    private function getThreshold(?int $storeId): float
    {
        return (float) $this->scopeConfig->getValue(self::XML_PATH_THRESHOLD, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
