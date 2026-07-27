<?php

namespace Kunal\Mod1\Model\ResourceModel\CustomerGroupSales;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';

    protected function _construct()
    {
        $this->_init(
            \Kunal\Mod1\Model\CustomerGroupSales::class,
            \Kunal\Mod1\Model\ResourceModel\CustomerGroupSales::class
        );
    }
}
