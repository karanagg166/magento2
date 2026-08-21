<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Magic360Custom\Block;

use Magento\Catalog\Block\Product\View\AbstractView;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class Product360Viewer extends AbstractView
{
    /**
     * Get 3D animation product image URL
     *
     * @return string
     */
    public function get3dProductImageUrl(): string
    {
        $customImage = $this->_scopeConfig->getValue(
            'magic360_custom/general/product_3d_image',
            ScopeInterface::SCOPE_STORE
        );

        $mediaUrl = $this->_storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);

        if ($customImage) {
            return $mediaUrl . 'magic360/' . $customImage;
        }

        return $mediaUrl . 'magic360/3d_product.png';
    }
}
