<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Karan_Popup::popup';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action for popup listing
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Karan_Popup::popup_menu');
        $resultPage->getConfig()->getTitle()->prepend(__('Storefront Popups'));

        return $resultPage;
    }
}
