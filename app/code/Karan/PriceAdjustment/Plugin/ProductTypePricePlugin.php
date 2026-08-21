<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\PriceAdjustment\Plugin;

use Magento\Catalog\Model\Product\Type\Price as ProductTypePrice;
use Magento\Catalog\Model\Product;

class ProductTypePricePlugin
{
    public const ADJUSTMENT_AMOUNT = 1.79;

    /**
     * Add $1.79 to final price computed by product type price model
     *
     * @param ProductTypePrice $subject
     * @param float|bool|null $result
     * @param float|null $qty
     * @param Product $product
     * @return float|bool|null
     */
    public function afterGetFinalPrice(ProductTypePrice $subject, $result, $qty, Product $product)
    {
        if ($result === null || $result === false) {
            return $result;
        }

        return (float)$result + self::ADJUSTMENT_AMOUNT;
    }

    /**
     * Add $1.79 to base price computed by product type price model
     *
     * @param ProductTypePrice $subject
     * @param float|bool|null $result
     * @param Product $product
     * @return float|bool|null
     */
    public function afterGetPrice(ProductTypePrice $subject, $result, Product $product)
    {
        if ($result === null || $result === false) {
            return $result;
        }

        return (float)$result + self::ADJUSTMENT_AMOUNT;
    }
}
