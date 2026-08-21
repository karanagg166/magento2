<?php

namespace StripeIntegration\Payments\Model;

#[\AllowDynamicProperties]
class Invoice extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('StripeIntegration\Payments\Model\ResourceModel\Invoice');
    }
}
