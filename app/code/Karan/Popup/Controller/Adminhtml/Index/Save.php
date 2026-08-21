<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Controller\Adminhtml\Index;

use Karan\Popup\Model\PopupFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Save extends Action
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
     * Save popup record
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('popup_id');
            $model = $this->popupFactory->create();

            if ($id) {
                $model->load($id);
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('Popup has been saved successfully.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['popup_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['popup_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
