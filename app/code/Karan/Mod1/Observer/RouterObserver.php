<?php

namespace Karan\Mod1\Observer;

use Magento\Framework\App\RouterList;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class RouterObserver implements ObserverInterface
{
    private RouterList $routerList;
    private LoggerInterface $logger;

    public function __construct(
        RouterList $routerList,
        LoggerInterface $logger
    ) {
        $this->routerList = $routerList;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        foreach ($this->routerList as $router) {
            $this->logger->info(get_class($router));
        }
    }
}