<?php

namespace Kunal\Mod1\Plugin;

use Magento\Catalog\Model\Product;
use Kunal\Mod1\Logger\Logger;

class ProductPlugin
{
    public function __construct(private Logger $logger)
    {
    }

    public function afterGetName(Product $subject, $result)
    {
        $price = (float)$subject->getPrice();

        if ($price < 20) {
            $label = ' Wholesale !!';
        } elseif ($price < 50) {
            $label = ' Super Sale !!';
        } else {
            $label = ' Premium !!';
        }

        // Logs why each product got its label. getData('price') is the raw
        // stored attribute (NULL for configurable parents -> 0), while
        // getPrice() runs through ProductPricePlugin's discount. Comparing the
        // two here explains the listing-vs-cart label mismatch.
        $this->logger->info('afterGetName', [
            'id'         => $subject->getId(),
            'sku'        => $subject->getSku(),
            'type'       => $subject->getTypeId(),
            'data_price' => $subject->getData('price'),
            'get_price'  => $subject->getPrice(),
            'label'      => trim($label),
        ]);

        return $result . $label;
    }
}
