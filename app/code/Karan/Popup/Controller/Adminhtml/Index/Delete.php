<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Controller\Adminhtml\Index;

use Karan\Popup\Model\PopupFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Karan_Popup::popup';

    /**
     * @var PopupFactory
     */
    private $popupFactory;

    /**
     * @param Context $context
     * @param PopupFactory $popupFactory
     */
    public function __construct(
        Context $context,
        PopupFactory $popupFactory
    ) {
        parent::__construct($context);
        $this->popupFactory = $popupFactory;
    }

    /**
     * Delete popup record
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('popup_id');

        if ($id) {
            try {
                $model = $this->popupFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('Popup has been deleted successfully.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
