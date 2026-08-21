<?php

namespace Karan\Mod1\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomerGroupSales extends AbstractDb
{
    public const TABLE_NAME = 'karan_customer_group_sales';

    protected function _construct()
    {
        $this->_init(
            self::TABLE_NAME,
            'entity_id'
        );
    }

    /**
     * Add one order's base grand total to the group's running total.
     *
     * Done as a single INSERT ... ON DUPLICATE KEY UPDATE so two concurrent
     * checkouts in the same group cannot overwrite each other's increment.
     */
    public function addOrderTotal(
        int $customerGroupId,
        float $amount,
        ?string $customerGroupCode = null,
        ?string $baseCurrencyCode = null,
        ?int $lastOrderId = null,
        ?string $lastOrderIncrementId = null
    ): void {
        $connection = $this->getConnection();

        $connection->insertOnDuplicate(
            $this->getMainTable(),
            [
                'customer_group_id'       => $customerGroupId,
                'customer_group_code'     => $customerGroupCode,
                'order_count'             => 1,
                'total_sales'             => $amount,
                'base_currency_code'      => $baseCurrencyCode,
                'last_order_id'           => $lastOrderId,
                'last_order_increment_id' => $lastOrderIncrementId,
            ],
            [
                'customer_group_code'     => new \Zend_Db_Expr('VALUES(customer_group_code)'),
                'order_count'             => new \Zend_Db_Expr('order_count + 1'),
                'total_sales'             => new \Zend_Db_Expr('total_sales + VALUES(total_sales)'),
                'base_currency_code'      => new \Zend_Db_Expr('VALUES(base_currency_code)'),
                'last_order_id'           => new \Zend_Db_Expr('VALUES(last_order_id)'),
                'last_order_increment_id' => new \Zend_Db_Expr('VALUES(last_order_increment_id)'),
            ]
        );
    }

    /**
     * Accumulated total for a single group (0.0 when the group has no orders yet).
     */
    public function getTotalByGroupId(int $customerGroupId): float
    {
        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable(), 'total_sales')
            ->where('customer_group_id = ?? ', $customerGroupId);

        return (float) $connection->fetchOne($select);
    }
}
