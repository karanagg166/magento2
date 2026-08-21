<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CustomerGroupSales\Model;

use Magento\Framework\Model\AbstractModel;

class GroupSales extends AbstractModel
{
    /**
     * Cache tag
     */
    public const CACHE_TAG = 'karan_customer_group_sales';

    /**
     * @var string
     */
    protected $_cacheTag = 'karan_customer_group_sales';

    /**
     * @var string
     */
    protected $_eventPrefix = 'karan_customer_group_sales';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(\Karan\CustomerGroupSales\Model\ResourceModel\GroupSales::class);
    }
}
