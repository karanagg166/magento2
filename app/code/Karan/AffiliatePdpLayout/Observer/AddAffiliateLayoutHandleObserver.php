<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AffiliatePdpLayout\Observer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AddAffiliateLayoutHandleObserver implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * Dynamically apply layout handle for general vs affiliated PDP
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $fullActionName = $this->request->getFullActionName();
        if ($fullActionName === 'catalog_product_view') {
            $affiliateParam = (string)$this->request->getParam('affiliate');
            $affParam = (string)$this->request->getParam('aff');

            $isAffiliate = ($affiliateParam === 'true' || $affiliateParam === '1' || $affParam === '1');

            /** @var \Magento\Framework\View\Layout $layout */
            $layout = $observer->getEvent()->getLayout();
            if ($isAffiliate) {
                $layout->getUpdate()->addHandle('catalog_product_view_affiliated');
            } else {
                $layout->getUpdate()->addHandle('catalog_product_view_general');
            }
        }
    }
}
