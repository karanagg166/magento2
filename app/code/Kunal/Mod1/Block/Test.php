<?php

namespace Kunal\Mod1\Block;

use Magento\Framework\View\Element\Template;

class Test extends Template
{
    protected function _toHtml()
    {   $html=parent::_toHtml();
        return "<h2>Inside _toHtml()</h2>".$html;
    }

    protected function _afterToHtml($html)
    {
        return $html . "<br><strong>Inside _afterToHtml()</strong>";
    }
}