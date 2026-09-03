<?php
declare(strict_types=1);

namespace Karan\CategoryPermissions\Plugin\Category;

use Magento\Catalog\Controller\Adminhtml\Category\Save;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Karan\CategoryPermissions\Api\PermissionManagementInterface;
use Psr\Log\LoggerInterface;

class SavePermissionPlugin
{
    private RequestInterface $request;
    private PermissionManagementInterface $permissionManagement;
    private Registry $registry;
    private ?LoggerInterface $logger;

    public function __construct(
        RequestInterface $request,
        PermissionManagementInterface $permissionManagement,
        Registry $registry,
        ?LoggerInterface $logger = null
    ) {
        $this->request = $request;
        $this->permissionManagement = $permissionManagement;
        $this->registry = $registry;
        $this->logger = $logger;
    }

    public function afterExecute(
        Save $subject,
        $result
    ) {
        /** @var \Magento\Catalog\Model\Category|null $category */
        $category = $this->registry->registry('current_category') ?: $this->registry->registry('category');

        $categoryId = $category && $category->getId()
            ? (int)$category->getId()
            : (int)($this->request->getParam('id') ?: $this->request->getParam('entity_id'));

        $permissions = $this->request->getParam(
            'category_permissions_rows',
            []
        );

        if (is_string($permissions)) {
            $permissions = json_decode($permissions, true) ?: [];
        }

        if (!$categoryId || !is_array($permissions) || empty($permissions)) {
            return $result;
        }

        try {
            $this->permissionManagement->savePermissions(
                $categoryId,
                $permissions
            );
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->error('Error saving category permissions: ' . $e->getMessage(), ['exception' => $e]);
            }
        }

        return $result;
    }
}