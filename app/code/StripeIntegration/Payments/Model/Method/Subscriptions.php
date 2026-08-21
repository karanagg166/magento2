<?php

namespace StripeIntegration\Payments\Model\Method;

#[\AllowDynamicProperties]
class Subscriptions extends \Magento\Payment\Model\Method\Adapter
{
    public function isAvailable(?\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return false;
    }
}
