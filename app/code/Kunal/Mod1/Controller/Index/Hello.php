<?php

namespace Kunal\Mod1\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Hello implements HttpGetActionInterface
{
    public function __construct(
        private RedirectFactory $redirectFactory
    ) {
    }

    public function execute()
    {
        $redirect = $this->redirectFactory->create();
        return $redirect->setPath('contact');
    }
}
