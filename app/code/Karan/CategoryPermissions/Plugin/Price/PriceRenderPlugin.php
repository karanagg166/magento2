<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Plugin\Price;

use Magento\Framework\Pricing\Render;
use Magento\Catalog\Model\Product;
use Karan\CategoryPermissions\Api\PermissionManagementInterface;

class PriceRenderPlugin
{
    private PermissionManagementInterface $permissionManagement;

    public function __construct(
        PermissionManagementInterface $permissionManagement
    ) {
        $this->permissionManagement = $permissionManagement;
    }

    public function aroundRender(
        Render $subject,
        callable $proceed,
        $priceCode,
        $saleableItem,
        array $arguments = []
    ) {
        if (!$saleableItem instanceof Product) {
            return $proceed($priceCode, $saleableItem, $arguments);
        }

        $categoryIds = $saleableItem->getCategoryIds();

        foreach ($categoryIds as $categoryId) {
            if (!$this->permissionManagement->canViewPrice((int)$categoryId)) {
                return '';
            }
        }

        return $proceed(
            $priceCode,
            $saleableItem,
            $arguments
        );
    }
}