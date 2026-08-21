<?php

namespace Karan\Mod1\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Karan\Mod1\Test;

class Index extends Action
{
    public function __construct(
        Context $context,
        private Test $test,
        private RawFactory $rawFactory
    ) {
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->rawFactory->create();
        $result->setContents($this->test->displayParams());
        return $result;
    }
}