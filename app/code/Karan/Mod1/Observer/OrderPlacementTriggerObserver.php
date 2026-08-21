<?php

namespace Karan\Mod1\Observer;

use Karan\Mod1\Logger\Logger;
use Karan\Mod1\Model\Config\OrderPlacementConfig;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Watches successful order placement and dispatches the custom
 * "custom_order_placement" event only for the designated customer groups.
 *
 * Bound to sales_model_service_quote_submit_success rather than
 * sales_order_place_after: that one fires inside Order::place(), before the
 * order is persisted, so a later save failure would leave us with a phantom
 * sale. This one fires after OrderManagement::place() returned a saved order.
 */
class OrderPlacementTriggerObserver implements ObserverInterface
{
    public const EVENT_NAME = 'custom_order_placement';

    private OrderPlacementConfig $config;
    private EventManager $eventManager;
    private Logger $logger;

    public function __construct(
        OrderPlacementConfig $config,
        EventManager $eventManager,
        Logger $logger
    ) {
        $this->config = $config;
        $this->eventManager = $eventManager;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        /** @var OrderInterface|null $order */
        $order = $observer->getEvent()->getData('order');

        if (!$order instanceof OrderInterface || !$order->getEntityId()) {
            return;
        }

        $storeId = $order->getStoreId();

        if (!$this->config->isEnabled($storeId)) {
            return;
        }

        $groupId = (int) $order->getCustomerGroupId();

        if (!$this->config->isDesignatedGroup($groupId, $storeId)) {
            return;
        }

        $this->eventManager->dispatch(
            self::EVENT_NAME,
            [
                'order'             => $order,
                'customer_group_id' => $groupId,
                'grand_total'       => (float) $order->getGrandTotal(),
                'base_grand_total'  => (float) $order->getBaseGrandTotal(),
                'store_id'          => $storeId,
            ]
        );

        $this->logger->info(sprintf(
            'Dispatched %s for order %s (customer group %d, base grand total %s).',
            self::EVENT_NAME,
            $order->getIncrementId(),
            $groupId,
            $order->getBaseGrandTotal()
        ));
    }
}
