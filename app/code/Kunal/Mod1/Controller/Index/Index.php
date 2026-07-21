<?php

namespace Kunal\Mod1\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Response\Http;
use Kunal\Mod1\Test;

class Index implements HttpGetActionInterface
{

    public function __construct(
        private Test $test,
        private Http $response
    ) {
    }

    public function execute()
    {
        return $this->response->setBody( $this->test->displayParams());
    }
}