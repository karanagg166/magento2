<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Controller\Adminhtml\Index;

use Karan\Popup\Model\PopupFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Edit extends Action
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
     * @var PopupFactory
     */
    private $popupFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param PopupFactory $popupFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        PopupFactory $popupFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->popupFactory = $popupFactory;
    }

    /**
     * Edit action for admin popup form
     *
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('popup_id');
        $model = $this->popupFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This popup no longer exists.'));
                /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Karan_Popup::popup_menu');
        $resultPage->getConfig()->getTitle()->prepend(
            $model->getId() ?  __('Edit Popup "%1"', $model->getTitle()) : __('New Storefront Popup')
        );

        return $resultPage;
    }
}
