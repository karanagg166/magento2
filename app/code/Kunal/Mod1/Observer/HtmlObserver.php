<?php

namespace Kunal\Mod1\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class HtmlObserver implements ObserverInterface
{
    protected LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $response = $observer->getEvent()->getResponse();

        $html = $response->getBody();

        $this->logger->info($html);
    }
}