<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Employee extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('employee_table', 'id');
    }
}
