<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Plugin\Cart;

use Magento\Checkout\Model\Cart;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Karan\CategoryPermissions\Api\PermissionManagementInterface;

class AddToCartPlugin
{
    private ProductRepositoryInterface $productRepository;
    private PermissionManagementInterface $permissionManagement;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        PermissionManagementInterface $permissionManagement
    ) {
        $this->productRepository = $productRepository;
        $this->permissionManagement = $permissionManagement;
    }

    public function beforeAddProduct(
        Cart $subject,
        $productInfo,
        $requestInfo = null
    ): array {
        $productId = is_object($productInfo)
            ? (int)$productInfo->getId()
            : (int)$productInfo;

        if ($productId <= 0) {
            return [$productInfo, $requestInfo];
        }

        $product = $this->productRepository->getById($productId);

        foreach ($product->getCategoryIds() as $categoryId) {
            if (!$this->permissionManagement->canAddToCart((int)$categoryId)) {
                throw new LocalizedException(
                    __('You are not allowed to add this product to the cart.')
                );
            }
        }

        return [$productInfo, $requestInfo];
    }
}