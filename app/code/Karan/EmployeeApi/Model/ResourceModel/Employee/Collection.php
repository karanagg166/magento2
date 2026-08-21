<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model\ResourceModel\Employee;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Initialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Karan\EmployeeApi\Model\Employee::class,
            \Karan\EmployeeApi\Model\ResourceModel\Employee::class
        );
    }
}
