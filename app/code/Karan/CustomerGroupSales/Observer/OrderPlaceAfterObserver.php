<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CustomerGroupSales\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

class OrderPlaceAfterObserver implements ObserverInterface
{
    private const XML_PATH_ENABLE = 'customer_group_sales/general/enable';
    private const XML_PATH_GROUPS = 'customer_group_sales/general/designated_customer_groups';

    /**
     * @var EventManagerInterface
     */
    private EventManagerInterface $eventManager;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param EventManagerInterface $eventManager
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        EventManagerInterface $eventManager,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        $this->eventManager = $eventManager;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Trigger custom event custom_order_placement if customer belongs to designated group
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $isEnabled = $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );

        if (!$isEnabled) {
            return;
        }

        $order = $observer->getEvent()->getOrder();
        if (!$order) {
            return;
        }

        $customerGroupId = (int) $order->getCustomerGroupId();
        $designatedGroupsConfig = (string) $this->scopeConfig->getValue(
            self::XML_PATH_GROUPS,
            ScopeInterface::SCOPE_STORE
        );

        $designatedGroups = array_filter(
            array_map('intval', explode(',', $designatedGroupsConfig))
        );

        // If no specific group is selected or customer group matches designated groups
        if (empty($designatedGroups) || in_array($customerGroupId, $designatedGroups, true)) {
            $grandTotal = (float) $order->getGrandTotal();
            
            $this->logger->info(sprintf(
                'Dispatching custom_order_placement event for Order #%s, Customer Group ID: %d, Amount: %.4f',
                $order->getIncrementId(),
                $customerGroupId,
                $grandTotal
            ));

            $this->eventManager->dispatch('custom_order_placement', [
                'order' => $order,
                'customer_group_id' => $customerGroupId,
                'grand_total' => $grandTotal
            ]);
        }
    }
}
