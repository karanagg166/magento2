<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Permission extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init(
            'karan_category_permission',
            'permission_id'
        );
    }
}