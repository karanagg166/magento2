<?php

namespace Karan\CategoryPermissions\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class YesNoOptions implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'value' => 1,
                'label' => __('Allow')
            ],
            [
                'value' => 0,
                'label' => __('Deny')
            ]
        ];
    }
}
