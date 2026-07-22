<?php

namespace Kunal\Mod1\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\RedirectFactory;
use Kunal\Mod1\Model\EmployeeFactory;
use Kunal\Mod1\Model\ResourceModel\Employee as EmployeeResource;

class EmployeeTest extends Action
{
    public function __construct(
        Context $context,
        private PageFactory $pageFactory,
        private EmployeeFactory $employeeFactory,
        private EmployeeResource $employeeResource,
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isPost()) {

            $employee = $this->employeeFactory->create();

            $employee->setData([
                'first_name' => $this->getRequest()->getParam('first_name'),
                'last_name'  => $this->getRequest()->getParam('last_name'),
                'email_id'   => $this->getRequest()->getParam('email_id')
            ]);

            $this->employeeResource->save($employee);

            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/*');
        }

        return $this->pageFactory->create();
    }
}