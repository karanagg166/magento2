<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CallForAvailability\Plugin\Catalog\Block\Product;

use Karan\CallForAvailability\Helper\Data as HelperData;
use Magento\Catalog\Block\Product\ListProduct;

class ListProductPlugin
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
     * Replace Out of Stock status with Call for Availability button on category product listing (PLP)
     *
     * @param ListProduct $subject
     * @param string $result
     * @return string
     */
    public function afterToHtml(ListProduct $subject, string $result): string
    {
        $contactUrl = $subject->getUrl('contact');
        $btnStyle = 'display: inline-block; background-color: #e45300; border: 1px solid #e45300; '
            . 'color: #ffffff; padding: 6px 12px; font-weight: bold; text-decoration: none; border-radius: 3px;';
        $buttonHtml = '<div class="actions-primary call-for-availability-plp" style="margin-top: 5px;">'
            . '<a href="' . $subject->escapeUrl($contactUrl) . '" '
            . 'class="action primary button call-for-availability-btn" style="' . $btnStyle . '">'
            . '<span>' . $subject->escapeHtml(__('Call For Availability')) . '</span>'
            . '</a></div>';

        return (string)preg_replace(
            '/<div class="stock unavailable">\s*<span>\s*Out of stock\s*<\/span>\s*<\/div>/i',
            $buttonHtml,
            $result
        );
    }
    

}