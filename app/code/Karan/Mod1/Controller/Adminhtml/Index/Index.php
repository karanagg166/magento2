<?php

namespace Karan\Mod1\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Magento_Backend::admin';
    protected $_publicActions = ['index'];

    protected function _isAllowed()
    {
        return true;
    }

    public function execute()
    {
        $access = $this->getRequest()->getParam('access');

        if ($access !== 'True') {
            die("Access Denied");
        }

        echo "Welcome Admin";
    }
}