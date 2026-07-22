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
        return $this->collectionFactory->create();
    }
}