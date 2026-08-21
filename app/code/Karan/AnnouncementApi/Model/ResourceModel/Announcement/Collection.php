<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Model\ResourceModel\Announcement;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Initialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Karan\AnnouncementApi\Model\Announcement::class,
            \Karan\AnnouncementApi\Model\ResourceModel\Announcement::class
        );
    }
}
