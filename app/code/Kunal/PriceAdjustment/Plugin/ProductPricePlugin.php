<?php
namespace Kunal\PriceAdjustment\Plugin;

use Magento\Catalog\Model\Product;

class ProductPricePlugin
{
    const ADJUSTMENT_AMOUNT = 1.79;

    /**
     * Set a flag before final price calculation to know we are in final price computation mode.
     *
     * @param Product $subject
     * @param float|null $qty
     * @return array
     */
    public function beforeGetFinalPrice(Product $subject, $qty = null)
    {
        $subject->setData('is_calculating_final_price', true);
        return [$qty];
    }

    /**
     * Adjust base price.
     *
     * @param Product $subject
     * @param float|bool|null $result
     * @return float|bool|null
     */
    public function afterGetPrice(Product $subject, $result)
    {
        if ($result === null || $result === false) {
            return $result;
        }
        if ($subject->getData('is_calculating_final_price')) {
            return $result;
        }
        return $result + self::ADJUSTMENT_AMOUNT;
    }

    /**
     * Adjust special price.
     *
     * @param Product $subj`ect
     * @param float|bool|null $result
     * @return float|bool|null
     */
    public function afterGetSpecialPrice(Product $subject, $result)
    {
        if ($result === null || $result === false) {
            return $result;
        }
        if ($subject->getData('is_calculating_final_price')) {
            return $result;
        }
        return $result + self::ADJUSTMENT_AMOUNT;
    }

    /**
     * Adjust final price and unset the flag.
     *
     * @param Product $subject
     * @param float|bool|null $result
     * @return float|bool|null
     */
    public function afterGetFinalPrice(Product $subject, $result)
    {
        $subject->unsetData('is_calculating_final_price');
        if ($result === null || $result === false) {
            return $result;
        }
        return $result + self::ADJUSTMENT_AMOUNT;
    }

    /**
     * Adjust minimal price.
     *
     * @param Product $subject
     * @param float|bool|null $result
     * @return float|bool|null
     */
    public function afterGetMinimalPrice(Product $subject, $result)
    {
        if ($result === null || $result === false) {
            return $result;
        }
        if ($subject->getData('is_calculating_final_price')) {
            return $result;
        }
        return $result + self::ADJUSTMENT_AMOUNT;
    }

    /**
     * Adjust maximal price.
     *
     * @param Product $subject
     * @param float|bool|null $result
     * @return float|bool|null
     */
    public function afterGetMaximalPrice(Product $subject, $result)
    {
        if ($result === null || $result === false) {
            return $result;
        }
        if ($subject->getData('is_calculating_final_price')) {
            return $result;
        }
        return $result + self::ADJUSTMENT_AMOUNT;
    }
}
