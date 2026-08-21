<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\CallForAvailability\Block;

use Karan\CallForAvailability\Helper\Data as HelperData;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;

class CallForAvailability extends Template
{
    /**
     * @var HelperData
     */
    private $helperData;

    /**
     * @var Registry
     */
    private $coreRegistry;

    /**
     * @param Context $context
     * @param HelperData $helperData
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        HelperData $helperData,
        Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helperData = $helperData;
        $this->coreRegistry = $coreRegistry;
    }

    /**
     * Get current product
     *
     * @return mixed
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product', $this->coreRegistry->registry('current_product'));
        }
        return $this->getData('product');
    }

    /**
     * Check if Call for Availability button should be displayed
     *
     * @return bool
     */
    public function isCallForAvailabilityRequired(): bool
    {
        $product = $this->getProduct();
        if ($product && $product->getId()) {
            return $this->helperData->isCallForAvailabilityRequired($product);
        }
        return false;
    }
     
    /**
     * Get contact us page URL
     *
     * @return string
     */
    public function getContactUrl(): string
    {
        return $this->getUrl('contact');
    }
}
