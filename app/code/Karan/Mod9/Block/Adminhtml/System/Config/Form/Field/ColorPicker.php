<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Mod9\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ColorPicker extends Field
{
    /**
     * Render color picker input element with apply button
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $value = $element->getEscapedValue() ?: '#ffffff';

        $element->setData('value', $value);

        $colorInputHtml = sprintf(
            '<input type="color" id="%s" name="%s" value="%s" ' .
            'style="vertical-align: middle; height: 35px; width: 60px; cursor: pointer; ' .
            'border: 1px solid #ccc; border-radius: 4px;" class="input-text"/>',
            $element->getHtmlId(),
            $element->getName(),
            $value
        );

        $applyBtnHtml = ' <button type="button" class="action-secondary" ' .
            'style="margin-left: 10px; vertical-align: middle;" ' .
            'onclick="document.getElementById(\'save\').click();">Apply Color</button>';

        return $colorInputHtml . $applyBtnHtml;
    }
}