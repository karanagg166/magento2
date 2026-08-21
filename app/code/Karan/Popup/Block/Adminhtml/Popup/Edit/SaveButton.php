<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Block\Adminhtml\Popup\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton implements ButtonProviderInterface
{
    /**
     * Get button data
     *
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Save Popup'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
