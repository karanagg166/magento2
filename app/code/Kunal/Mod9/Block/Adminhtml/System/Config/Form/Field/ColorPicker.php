<?php

namespace Kunal\Mod9\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class ColorPicker extends Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $value = $element->getEscapedValue() ?: '#000000';

        $element->setData('value', $value);

        return sprintf(
            '<input type="color" id="%s" name="%s" value="%s" class="input-text"/>',
            $element->getHtmlId(),
            $element->getName(),
            $value
        );
    }
}