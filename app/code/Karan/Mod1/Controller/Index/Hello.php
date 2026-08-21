<?php

namespace Karan\Mod1\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Hello extends Action
{
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('adidas-t-shirt.html');
        return $resultRedirect;
    }
}
