<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CustomerGroupSales\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

use Karan\CustomerGroupSales\Model\GroupSales as GroupSalesModel;

class GroupSales extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('karan_customer_group_sales', 'entity_id');
    }

    /**
     * Load record by customer group ID
     *
     * @param GroupSalesModel $groupSales
     * @param int $customerGroupId
     * @return $this
     */
    public function loadByCustomerGroupId(GroupSalesModel $groupSales, int $customerGroupId): self
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('customer_group_id = ?? ', $customerGroupId);

        $data = $connection->fetchRow($select);
        if ($data) {
            $groupSales->setData($data);
        }

        return $this;
    }
}
