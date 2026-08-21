<?php

namespace StripeIntegration\Payments\Model\Adminhtml\Source;

#[\AllowDynamicProperties]
class Mode
{
    const TEST = 'test';
    const LIVE = 'live';

    public function toOptionArray()
    {
        return [
            [
                'value' => Mode::TEST,
                'label' => __('Test')
            ],
            [
                'value' => Mode::LIVE,
                'label' => __('Live')
            ],
        ];
    }
}
