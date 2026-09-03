<?php
declare(strict_types=1);

namespace Karan\CategoryPermissions\Model;

use Karan\CategoryPermissions\Api\PermissionManagementInterface;
use Karan\CategoryPermissions\Model\PermissionFactory;
use Karan\CategoryPermissions\Model\ResourceModel\Permission as PermissionResource;
use Karan\CategoryPermissions\Model\ResourceModel\Permission\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class PermissionManagement implements PermissionManagementInterface
{
    private PermissionFactory $permissionFactory;
    private PermissionResource $permissionResource;
    private CollectionFactory $collectionFactory;
    private StoreManagerInterface $storeManager;
    private CustomerSession $customerSession;
    private CategoryRepositoryInterface $categoryRepository;

    public function __construct(
        PermissionFactory $permissionFactory,
        PermissionResource $permissionResource,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->permissionFactory = $permissionFactory;
        $this->permissionResource = $permissionResource;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->customerSession = $customerSession;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Check if customer can browse category
     *
     * @param int $categoryId
     * @return bool
     */
  public function canBrowse(int $categoryId): bool
{
    while ($categoryId > 0) {

        $permission = $this->getPermission($categoryId);

        /*
         * If a rule exists and browsing is denied,
         * immediately deny access.
         */
        if (
            $permission !== null &&
            !(bool)$permission->getData('can_browse')
        ) {
            return false;
        }

        try {
            $category = $this->categoryRepository->get($categoryId);
        } catch (\Exception $e) {
            break;
        }

        $parentId = (int)$category->getParentId();

        /*
         * No more useful parent categories.
         */
        if ($parentId <= 1 || $parentId === $categoryId) {
            break;
        }

        $categoryId = $parentId;
    }

    return true;
}
    /**
     * Check if customer can view price in category
     *
     * @param int $categoryId
     * @return bool
     */
   public function canViewPrice(int $categoryId): bool
{
    while ($categoryId > 0) {

        $permission = $this->getPermission($categoryId);

        if (
            $permission !== null &&
            !(bool)$permission->getData('can_view_price')
        ) {
            return false;
        }

        try {
            $category = $this->categoryRepository->get($categoryId);
        } catch (\Exception $e) {
            break;
        }

        $parentId = (int)$category->getParentId();

        if ($parentId <= 1 || $parentId === $categoryId) {
            break;
        }
        $categoryId = $parentId;
    }
  
    return true;
}


public function canAddToCart(int $categoryId): bool
{
    while ($categoryId > 0) {

        $permission = $this->getPermission($categoryId);

        if (
            $permission !== null &&
            !(bool)$permission->getData('can_add_to_cart')
        ) {
            return false;
        }

        try {
            $category = $this->categoryRepository->get($categoryId);
        } catch (\Exception $e) {
            break;
        }

        $parentId = (int)$category->getParentId();

        if ($parentId <= 1 || $parentId === $categoryId) {
            break;
        }

        $categoryId = $parentId;
    }

    return true;
}

    /**
     * Get permission rule for category, website, and customer group
     *
     * @param int $categoryId
     * @return \Magento\Framework\DataObject|null
     */
    private function getPermission(int $categoryId)
    {
        $websiteId = (int)$this->storeManager
            ->getWebsite()
            ->getId();

        $customerGroupId = (int)$this->customerSession
            ->getCustomerGroupId();

        $collection = $this->collectionFactory->create();

        $collection
            ->addFieldToFilter('category_id', $categoryId)
            ->addFieldToFilter('website_id', $websiteId)
            ->addFieldToFilter('customer_group_id', $customerGroupId);

        $permission = $collection->getFirstItem();

        /*
         * No rule means normal Magento behavior
         */
        
        if (!$permission->getId()) {
            return null;
        }

        return $permission;
    }

    /**
     * Save category permissions
     *
     * @param int $categoryId
     * @param array $permissions
     * @return void
     */
    public function savePermissions(int $categoryId, array $permissions): void
    {
        if ($categoryId <= 0) {
            return;
        }

        foreach ($permissions as $permissionData) {
            if (!is_array($permissionData)) {
                continue;
            }

            /*
             * DELETE
             */
            $isDelete = !empty($permissionData['delete'])
                && $permissionData['delete'] !== 'false'
                && $permissionData['delete'] !== '0';

            if ($isDelete) {
                if (!empty($permissionData['permission_id'])) {
                    $permission = $this->permissionFactory->create();
                    $this->permissionResource->load(
                        $permission,
                        (int)$permissionData['permission_id']
                    );

                    if ($permission->getId() && (int)$permission->getData('category_id') === $categoryId) {
                        $this->permissionResource->delete($permission);
                    }
                }
                continue;
            }

            /*
             * Make sure required values exist and are numeric
             */
            if (
                !isset($permissionData['website_id']) ||
                $permissionData['website_id'] === '' ||
                !isset($permissionData['customer_group_id']) ||
                $permissionData['customer_group_id'] === ''
            ) {
                continue;
            }

            $websiteId = (int)$permissionData['website_id'];
            $customerGroupId = (int)$permissionData['customer_group_id'];

            /*
             * Check whether this combination already exists to prevent duplicate unique key constraint error
             */
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('category_id', $categoryId)
                ->addFieldToFilter('website_id', $websiteId)
                ->addFieldToFilter('customer_group_id', $customerGroupId);

            $permission = $collection->getFirstItem();

            if (!$permission || !$permission->getId()) {
                if (!empty($permissionData['permission_id'])) {
                    $permission = $this->permissionFactory->create();
                    $this->permissionResource->load(
                        $permission,
                        (int)$permissionData['permission_id']
                    );

                    if (!$permission->getId() || (int)$permission->getData('category_id') !== $categoryId) {
                        $permission = $this->permissionFactory->create();
                    }
                } else {
                    $permission = $this->permissionFactory->create();
                }
            }

            /*
             * SET DATA
             */
            $canBrowse = isset($permissionData['can_browse']) && $permissionData['can_browse'] !== ''
                ? (int)$permissionData['can_browse']
                : 1;

            $canViewPrice = isset($permissionData['can_view_price']) && $permissionData['can_view_price'] !== ''
                ? (int)$permissionData['can_view_price']
                : 1;

            $canAddToCart = isset($permissionData['can_add_to_cart']) && $permissionData['can_add_to_cart'] !== ''
                ? (int)$permissionData['can_add_to_cart']
                : 1;

            $permission->setData('category_id', $categoryId);
            $permission->setData('website_id', $websiteId);
            $permission->setData('customer_group_id', $customerGroupId);
            $permission->setData('can_browse', $canBrowse);
            $permission->setData('can_view_price', $canViewPrice);
            $permission->setData('can_add_to_cart', $canAddToCart);

            /*
             * INSERT or UPDATE
             */
            $this->permissionResource->save($permission);
        }
    }

}


