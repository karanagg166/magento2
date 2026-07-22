<?php
namespace Kunal\Mod1\Block;

use Magento\Framework\View\Element\Template;
use Kunal\Mod1\Model\ResourceModel\Employee\CollectionFactory;

class Table extends Template
{
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->collectionFactory = $collectionFactory;
    }

    public function getEmployees()
    {
        $sort = $this->getRequest()
            ->getParam('sort');

        $direction = $this->getRequest()
            ->getParam('dir', 'ASC');

        $allowed = [
            'employee_id',
            'first_name',
            'last_name',
            'email_id'
        ];

        if (!in_array($sort, $allowed)) {
            $sort = 'employee_id';
        }

        return $this->collectionFactory
            ->create()
            ->setOrder(
                $sort,
                $direction
            );
    }
}