<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CallForAvailability\Plugin\Catalog\Block\Product;

use Karan\CallForAvailability\Helper\Data as HelperData;
use Magento\Catalog\Block\Product\View;

class ViewPlugin
{
    /**
     * @var HelperData
     */
    private $helperData;

    /**
     * @param HelperData $helperData
     */
    public function __construct(
        HelperData $helperData
    ) {
        $this->helperData = $helperData;
    }

    /**
     * Replace Out of Stock status with Call for Availability button on PDP
     *
     * @param View $subject
     * @param string $result
     * @return string
     */
    public function afterToHtml(View $subject, string $result): string
    {
        $product = $subject->getProduct();
        if ($product && $this->helperData->isCallForAvailabilityRequired($product)) {
            $contactUrl = $subject->getUrl('contact');
            $btnStyle = 'display: inline-block; background-color: #e45300; border: 1px solid #e45300; '
                . 'color: #ffffff; padding: 10px 20px; font-weight: bold; text-decoration: none;';
            $buttonHtml = '<div class="actions call-for-availability-pdp" '
                . 'style="margin-top: 15px; margin-bottom: 15px;">'
                . '<a href="' . $subject->escapeUrl($contactUrl) . '" '
                . 'class="action primary button call-for-availability-btn" style="' . $btnStyle . '">'
                . '<span>' . $subject->escapeHtml(__('Call For Availability')) . '</span>'
                . '</a></div>';

            $result = str_replace(
                '<div class="stock unavailable" title="Availability"><span>Out of stock</span></div>',
                $buttonHtml,
                $result
            );
            $result = str_replace(
                '<div class="stock unavailable"><span>Out of stock</span></div>',
                $buttonHtml,
                $result
            );
        }

        return $result;
    }
}
