<?php
namespace Kunal\Mod1\Model\ResourceModel\Employee;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Kunal\Mod1\Model\Employee::class,
            \Kunal\Mod1\Model\ResourceModel\Employee::class
        );
    }
}