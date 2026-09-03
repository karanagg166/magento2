<?php

declare(strict_types=1);

namespace Karan\CategoryPermissions\Plugin\Category;

use Magento\Catalog\Model\Category\DataProvider;
use Karan\CategoryPermissions\Model\ResourceModel\Permission\CollectionFactory;

class CategoryFormDataProviderPlugin
{
    private CollectionFactory $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function afterGetData(
        DataProvider $subject,
        array $result
    ): array {
        foreach ($result as $categoryId => &$categoryData) {

            $collection = $this->collectionFactory->create();

            $collection->addFieldToFilter(
                'category_id',
                (int)$categoryId
            );

            $permissions = [];

            foreach ($collection as $permission) {
                $permissions[] = [
                    'permission_id' => (int)$permission->getId(),
                    'website_id' => (int)$permission->getData('website_id'),
                    'customer_group_id' => (int)$permission->getData('customer_group_id'),
                    'can_browse' => (int)$permission->getData('can_browse'),
                    'can_view_price' => (int)$permission->getData('can_view_price'),
                    'can_add_to_cart' => (int)$permission->getData('can_add_to_cart')
                ];
            }

            $categoryData['category_permissions_rows'] = $permissions;
        }

        return $result;
    }
}