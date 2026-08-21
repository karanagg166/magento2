<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CustomerGroupSales\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Karan\CustomerGroupSales\Model\GroupSalesFactory;
use Karan\CustomerGroupSales\Model\ResourceModel\GroupSales as GroupSalesResource;
use Psr\Log\LoggerInterface;

class RecordGroupSalesObserver implements ObserverInterface
{
    /**
     * @var GroupSalesFactory
     */
    private GroupSalesFactory $groupSalesFactory;

    /**
     * @var GroupSalesResource
     */
    private GroupSalesResource $groupSalesResource;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param GroupSalesFactory $groupSalesFactory
     * @param GroupSalesResource $groupSalesResource
     * @param LoggerInterface $logger
     */
    public function __construct(
        GroupSalesFactory $groupSalesFactory,
        GroupSalesResource $groupSalesResource,
        LoggerInterface $logger
    ) {
        $this->groupSalesFactory = $groupSalesFactory;
        $this->groupSalesResource = $groupSalesResource;
        $this->logger = $logger;
    }

    /**
     * Store or update customer group total sales in database
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $event = $observer->getEvent();
        $customerGroupId = (int) $event->getData('customer_group_id');
        $grandTotal = (float) $event->getData('grand_total');

        if (!$customerGroupId) {
            $order = $event->getData('order');
            if ($order) {
                $customerGroupId = (int) $order->getCustomerGroupId();
                $grandTotal = (float) $order->getGrandTotal();
            }
        }

        try {
            $groupSales = $this->groupSalesFactory->create();
            $this->groupSalesResource->loadByCustomerGroupId($groupSales, $customerGroupId);

            if ($groupSales->getId()) {
                $newTotal = (float) $groupSales->getTotalSales() + $grandTotal;
                $newCount = (int) $groupSales->getOrderCount() + 1;
                $groupSales->setTotalSales($newTotal);
                $groupSales->setOrderCount($newCount);
            } else {
                $groupSales->setCustomerGroupId($customerGroupId);
                $groupSales->setTotalSales($grandTotal);
                $groupSales->setOrderCount(1);
            }

            $this->groupSalesResource->save($groupSales);

            $this->logger->info(sprintf(
                'Updated customer group sales for Group ID: %d. Total Sales: %.4f, Order Count: %d',
                $customerGroupId,
                (float) $groupSales->getTotalSales(),
                (int) $groupSales->getOrderCount()
            ));
        } catch (\Exception $e) {
            $this->logger->error(sprintf(
                'Failed to record customer group sales for Group ID %d: %s',
                $customerGroupId,
                $e->getMessage()
            ));
        }
    }
}
