<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Announcement extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('karan_announcement', 'entity_id');
    }
}
