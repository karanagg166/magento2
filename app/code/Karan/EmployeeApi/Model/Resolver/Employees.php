<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model\Resolver;

use Karan\EmployeeApi\Model\ResourceModel\Employee\CollectionFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Employees implements ResolverInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        ?array $value = null,
        ?array $args = null
    ) {
        $collection = $this->collectionFactory->create();

        if (isset($args['filter'])) {
            foreach ($args['filter'] as $filterField => $condition) {
                if (is_array($condition)) {
                    foreach ($condition as $operator => $val) {
                        $collection->addFieldToFilter($filterField, [$operator => $val]);
                    }
                } else {
                    $collection->addFieldToFilter($filterField, ['eq' => $condition]);
                }
            }
        }

        $items = [];
        foreach ($collection as $employee) {
            $items[] = [
                'entity_id' => (int)$employee->getId(),
                'name' => $employee->getName(),
                'email' => $employee->getEmail(),
                'department' => $employee->getDepartment(),
                'position' => $employee->getPosition(),
                'salary' => (float)$employee->getSalary()
            ];
        }

        return [
            'items' => $items,
            'total_count' => count($items)
        ];
    }
}
