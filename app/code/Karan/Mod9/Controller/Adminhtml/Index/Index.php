<?php

namespace Karan\Mod9\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Action implements HttpGetActionInterface
{
  
    const ADMIN_RESOURCE = 'Karan_Mod9::display';
    private StoreManagerInterface $storeManager;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /**
     * Redirect the admin menu click to the frontend controller.
     */
    public function execute()
    {
        $baseUrl = $this->storeManager->getStore()->getBaseUrl();

        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($baseUrl . 'mod9/index/index');

        return $resultRedirect;
    }
}
