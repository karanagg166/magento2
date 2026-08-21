<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CustomerGroupSales\Model\ResourceModel\GroupSales;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(
            \Karan\CustomerGroupSales\Model\GroupSales::class,
            \Karan\CustomerGroupSales\Model\ResourceModel\GroupSales::class
        );
    }
}
