<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Popup extends AbstractDb
{
    /**
     * Initialize main table and primary key
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('karan_popup', 'popup_id');
    }
}
