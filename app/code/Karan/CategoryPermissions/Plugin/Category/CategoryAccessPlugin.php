<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Plugin\Category;

use Magento\Catalog\Controller\Category\View;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Karan\CategoryPermissions\Api\PermissionManagementInterface;

class CategoryAccessPlugin
{
    private RequestInterface $request;
    private RedirectFactory $redirectFactory;
    private PermissionManagementInterface $permissionManagement;

    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory,
        PermissionManagementInterface $permissionManagement
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->permissionManagement = $permissionManagement;
    }

    public function aroundExecute(
        View $subject,
        callable $proceed
    ) {
        $categoryId = (int)$this->request->getParam('id');

        if ($categoryId <= 0) {
            return $proceed();
        }

        $canBrowse = $this->permissionManagement->canBrowse(
            $categoryId
        );
 
        if (!$canBrowse) {
            $resultRedirect = $this->redirectFactory->create();

            $resultRedirect->setPath('noroute');

            return $resultRedirect;
        }

        return $proceed();
    }
}