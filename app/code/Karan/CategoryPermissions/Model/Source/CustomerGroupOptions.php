<?php

namespace Karan\CategoryPermissions\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;

class CustomerGroupOptions implements OptionSourceInterface
{
    private CollectionFactory $groupCollectionFactory;

    public function __construct(
        CollectionFactory $groupCollectionFactory
    ) {
        $this->groupCollectionFactory = $groupCollectionFactory;
    }

    public function toOptionArray(): array
    {
        $options = [];

        $collection = $this->groupCollectionFactory->create();

        foreach ($collection as $group) {
            $options[] = [
                'value' => $group->getId(),
                'label' => $group->getCustomerGroupCode()
            ];
        }

        return $options;
    }
}
