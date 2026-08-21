<?php

namespace StripeIntegration\Payments\Model\ResourceModel;

#[\AllowDynamicProperties]
class Invoice extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('stripe_invoices', 'id');
    }
}
