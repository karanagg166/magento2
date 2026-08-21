<?php

namespace StripeIntegration\Payments\Model\Adminhtml\Source;

#[\AllowDynamicProperties]
class StripeRadar
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 0,
                'label' => __('Disabled')
            ),
            array(
                'value' => 10,
                'label' => __('Enabled')
            )
        );
    }
}
