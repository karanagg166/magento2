<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class NewAction extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    public const ADMIN_RESOURCE = 'Karan_Popup::popup';

    /**
     * Forward to edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */
        $resultForward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        return $resultForward->forward('edit');
    }
}
