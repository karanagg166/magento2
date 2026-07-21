<?php

namespace Kunal\Mod1\Plugin;

use Magento\Catalog\Block\Product\View\Description;

class DescriptionPlugin
{
    public function afterGetProduct(
        Description $subject,
        $product
    ) {
        $product->setDescription('sample description');

        return $product;
    }
}