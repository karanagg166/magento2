<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Model;

use Magento\Framework\Model\AbstractModel;

class Popup extends AbstractModel
{
    /**
     * @var string
     */
    protected $_cacheTag = 'karan_popup';

    /**
     * @var string
     */
    protected $_eventPrefix = 'karan_popup';

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Karan\Popup\Model\ResourceModel\Popup::class);
    }
}
