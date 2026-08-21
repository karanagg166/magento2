<?php
namespace Karan\Mod1\Model\ResourceModel\Employee;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Karan\Mod1\Model\Employee::class,
            \Karan\Mod1\Model\ResourceModel\Employee::class
        );
    }
}