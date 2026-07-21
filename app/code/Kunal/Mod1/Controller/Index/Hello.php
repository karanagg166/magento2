<?php

namespace Kunal\Mod1\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

use Magento\Framework\Controller\Result\RedirectFactory;

class Hello extends Action
{
    
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }
    public function execute()
    {
        $this->_redirect('lenovo-loq.html');
    }
}
