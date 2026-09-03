<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Model\ResourceModel\Permission;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Karan\CategoryPermissions\Model\Permission as PermissionModel;
use Karan\CategoryPermissions\Model\ResourceModel\Permission as PermissionResource;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(
            PermissionModel::class,
            PermissionResource::class
        );
    }
}