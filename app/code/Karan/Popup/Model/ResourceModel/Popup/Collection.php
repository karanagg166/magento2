<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Model\ResourceModel\Popup;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'popup_id';

    /**
     * Initialize collection
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Karan\Popup\Model\Popup::class,
            \Karan\Popup\Model\ResourceModel\Popup::class
        );
    }
}
