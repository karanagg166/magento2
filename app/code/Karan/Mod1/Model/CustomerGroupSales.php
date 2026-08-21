<?php

namespace Karan\Mod1\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * One accumulated sales row per customer group.
 */
class CustomerGroupSales extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(
            \Karan\Mod1\Model\ResourceModel\CustomerGroupSales::class
        );
    }
}
