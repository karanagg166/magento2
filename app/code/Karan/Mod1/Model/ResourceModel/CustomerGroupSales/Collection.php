<?php

namespace Karan\Mod1\Model\ResourceModel\CustomerGroupSales;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            \Karan\Mod1\Model\CustomerGroupSales::class,
            \Karan\Mod1\Model\ResourceModel\CustomerGroupSales::class
        );
    }
}
