<?php

namespace StripeIntegration\Payments\Model\Adminhtml\Source;

#[\AllowDynamicProperties]
class Currencies
{
    public function toOptionArray()
    {
        return [
            [
                'value' => 0,
                'label' => __('All Currencies')
            ],
            [
                'value' => 1,
                'label' => __('Specific Currencies')
            ],
        ];
    }
}
