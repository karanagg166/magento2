<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Model;

use Magento\Framework\Model\AbstractModel;
use Karan\CategoryPermissions\Model\ResourceModel\Permission as PermissionResource;

class Permission extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(PermissionResource::class);
    }
}