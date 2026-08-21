<?php
namespace Karan\Mod1\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RedirectObserver implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        $response = $observer->getEvent()->getAction()->getResponse();
        $response->setRedirect('/contact');
    }
}
