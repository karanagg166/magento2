<?php
namespace Kunal\Mod1\Block;
use Magento\Framework\View\Element\Template;

class Message extends Template{
    
    protected function _afterToHtml($html)
    {
        return $html . '<br><strong>This message comes from _afterToHtml()</strong>';
    }
}