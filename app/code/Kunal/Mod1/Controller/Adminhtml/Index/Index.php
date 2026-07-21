<?php

namespace Kunal\Mod1\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Magento_Backend::admin';

    public function execute()
    {
        $access = $this->getRequest()->getParam('access');

        if ($access !== 'True') {
            die("Access Denied");
        }

        echo "Welcome Admin";
    }
}