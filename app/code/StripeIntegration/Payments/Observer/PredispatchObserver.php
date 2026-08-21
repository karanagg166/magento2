<?php

namespace StripeIntegration\Payments\Observer;

use Magento\Framework\Event\ObserverInterface;
use StripeIntegration\Payments\Helper\Logger;

#[\AllowDynamicProperties]
class PredispatchObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    public function __construct(
        \Magento\Framework\Event\ManagerInterface $eventManager
    )
    {
        $this->_eventManager = $eventManager;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!empty($_SERVER['REQUEST_URI']) && stripos($_SERVER['REQUEST_URI'],"directory/currency/switch") !== false)
            $this->_eventManager->dispatch('stripe_payments_currency_switch');
    }
}
