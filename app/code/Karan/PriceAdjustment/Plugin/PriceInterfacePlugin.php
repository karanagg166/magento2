<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\PriceAdjustment\Plugin;

use Magento\Framework\Pricing\Price\PriceInterface;

class PriceInterfacePlugin
{
    public const ADJUSTMENT_AMOUNT = 1.79;

    /**
     * @var bool
     */
    private static $isCalculating = false;

    /**
     * Adjust price value returned by pricing models.
     *
     * @param PriceInterface $subject
     * @param float|bool|null $result
     * @return float|bool|null
     */
    public function afterGetValue(PriceInterface $subject, $result)
    {
        if (self::$isCalculating) {
            return $result;
        }
        if ($result === null || $result === false) {
            return $result;
        }

        self::$isCalculating = true;
        try { 
            $result = (float)$result + self::ADJUSTMENT_AMOUNT;
        } finally {
            self::$isCalculating = false;
        }

        return $result;
    }
}
