<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Plugin\Product;

use Magento\Catalog\Controller\Product\View;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Karan\CategoryPermissions\Api\PermissionManagementInterface;

class ProductAccessPlugin
{
    private RequestInterface $request;
    private ProductRepositoryInterface $productRepository;
    private PermissionManagementInterface $permissionManagement;
    private RedirectFactory $redirectFactory;

    public function __construct(
        RequestInterface $request,
        ProductRepositoryInterface $productRepository,
        PermissionManagementInterface $permissionManagement,
        RedirectFactory $redirectFactory
    ) {
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->permissionManagement = $permissionManagement;
        $this->redirectFactory = $redirectFactory;
    }

    public function aroundExecute(
        View $subject,
        callable $proceed
    ) {
        $productId = (int)$this->request->getParam('id');

        if ($productId <= 0) {
            return $proceed();
        }

        try {
            $product = $this->productRepository->getById($productId);
        } catch (\Exception $e) {
            return $proceed();
        }

        $categoryIds = $product->getCategoryIds();

        /*
         * Product with no categories:
         * allow normal Magento behavior
         */
        if (empty($categoryIds)) {
            return $proceed();
        }

        /*
         * Product should be accessible if at least
         * one of its categories is accessible.
         */
       foreach ($categoryIds as $categoryId) {
    if (!$this->permissionManagement->canBrowse((int)$categoryId)) {

        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setPath('noroute');

        return $resultRedirect;
    }
    
}

return $proceed();
        /*
         * All categories restricted
         */
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setPath('noroute');

        return $resultRedirect;
    }
}