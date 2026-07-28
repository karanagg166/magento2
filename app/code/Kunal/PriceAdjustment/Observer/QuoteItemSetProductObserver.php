<?php
namespace Kunal\PriceAdjustment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class QuoteItemSetProductObserver implements ObserverInterface
{
    /**
     * Set custom adjusted price on quote items during catalog cart additions and updates.
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = $observer->getEvent()->getQuoteItem();
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();

        if ($item && $product) {
            $price = (float)$product->getFinalPrice();
            $item->setCustomPrice($price);
            $item->setOriginalCustomPrice($price);
            $item->getProduct()->setIsSuperMode(true);
        }
    }
}
