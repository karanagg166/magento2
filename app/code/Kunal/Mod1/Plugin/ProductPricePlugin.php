<?php

namespace Kunal\Mod1\Plugin;

use Magento\Catalog\Model\Product;

class ProductPricePlugin
{
    public function afterGetPrice(Product $subject, $result)
    {
        if ($result >= 20 && $result < 50) {
            return round($result * 0.85, 2);
        }

        return $result;
    }
}
