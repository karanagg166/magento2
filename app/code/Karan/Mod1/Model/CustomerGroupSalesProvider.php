<?php

namespace Karan\Mod1\Model;

use Karan\Mod1\Model\ResourceModel\CustomerGroupSales as CustomerGroupSalesResource;
use Karan\Mod1\Model\ResourceModel\CustomerGroupSales\CollectionFactory;

/**
 * Read side of the stored customer-group sales totals.
 *
 * Inject this anywhere the accumulated figures are needed later — blocks,
 * controllers, cron, reports.
 */
class CustomerGroupSalesProvider
{
    private CustomerGroupSalesFactory $salesFactory;
    private CustomerGroupSalesResource $salesResource;
    private CollectionFactory $collectionFactory;

    public function __construct(
        CustomerGroupSalesFactory $salesFactory,
        CustomerGroupSalesResource $salesResource,
        CollectionFactory $collectionFactory
    ) {
        $this->salesFactory = $salesFactory;
        $this->salesResource = $salesResource;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Accumulated base grand total for one group; 0.0 when nothing is stored yet.
     */
    public function getTotalByGroupId(int $customerGroupId): float
    {
        return $this->salesResource->getTotalByGroupId($customerGroupId);
    }

    /**
     * Full stored row for one group, or null when the group has no orders yet.
     */
    public function getByGroupId(int $customerGroupId): ?CustomerGroupSales
    {
        $model = $this->salesFactory->create();
        $this->salesResource->load($model, $customerGroupId, 'customer_group_id');

        return $model->getId() ?  $model : null;
    }

    /**
     * Every stored group total, highest spending group first.
     *
     * @return CustomerGroupSales[]
     */
    public function getAll(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->setOrder('total_sales', 'DESC');

        return array_values($collection->getItems());
    }

    /**
     * Sum of every stored group total.
     */
    public function getGrandTotal(): float
    {
        $total = 0.0;
        foreach ($this->getAll() as $row) {
            $total += (float) $row->getData('total_sales');
        }

        return $total;
    }
}
