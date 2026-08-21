<?php

namespace Karan\Mod1\Observer;

use Karan\Mod1\Logger\Logger;
use Karan\Mod1\Model\ResourceModel\CustomerGroupSales as CustomerGroupSalesResource;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;

/**
 * Handles the custom "custom_order_placement" event: adds the order's total to
 * the running sales total stored for that customer group.
 */
class CustomerGroupSalesObserver implements ObserverInterface
{
    private CustomerGroupSalesResource $salesResource;
    private GroupRepositoryInterface $groupRepository;
    private Logger $logger;

    public function __construct(
        CustomerGroupSalesResource $salesResource,
        GroupRepositoryInterface $groupRepository,
        Logger $logger
    ) {
        $this->salesResource = $salesResource;
        $this->groupRepository = $groupRepository;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();

        /** @var OrderInterface|null $order */
        $order = $event->getData('order');
        $groupId = (int) $event->getData('customer_group_id');

        // Base grand total keeps multi-currency stores summable.
        $amount = (float) $event->getData('base_grand_total');

        if ($amount <= 0) {
            $this->logger->info(sprintf(
                'custom_order_placement ignored for customer group %d: non-positive total.',
                $groupId
            ));
            return;
        }

        try {
            $this->salesResource->addOrderTotal(
                $groupId,
                $amount,
                $this->getGroupCode($groupId),
                $order ?  $order->getBaseCurrencyCode() : null,
                $order && $order->getEntityId() ?  (int) $order->getEntityId() : null,
                $order ?  $order->getIncrementId() : null
            );

            $this->logger->info(sprintf(
                'Customer group %d sales total increased by %s (order %s).',
                $groupId,
                $amount,
                $order ?  $order->getIncrementId() : 'n/a'
            ));
        } catch (\Throwable $e) {
            // Never let reporting break a completed checkout.
            $this->logger->error(
                'Failed to store customer group sales total: ' . $e->getMessage()
            );
        }
    }

    /**
     * Human-readable group label, stored alongside the total for convenience.
     */
    private function getGroupCode(int $groupId): ?string
    {
        try {
            return $this->groupRepository->getById($groupId)->getCode();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
