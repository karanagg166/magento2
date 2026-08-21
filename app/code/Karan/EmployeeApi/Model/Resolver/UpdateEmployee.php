<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model\Resolver;

use Karan\EmployeeApi\Api\EmployeeRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class UpdateEmployee implements ResolverInterface
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;

    /**
     * @param EmployeeRepositoryInterface $employeeRepository
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository
    ) {
        $this->employeeRepository = $employeeRepository;
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
        if (!isset($args['id']) || !isset($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('Invalid arguments'));
        }

        try {
            $employee = $this->employeeRepository->getById((int)$args['id']);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__('Employee with ID %1 not found', $args['id']));
        }

        $input = $args['input'];
        if (isset($input['name'])) {
            $employee->setName($input['name']);
        }
        if (isset($input['email'])) {
            $employee->setEmail($input['email']);
        }
        if (isset($input['department'])) {
            $employee->setDepartment($input['department']);
        }
        if (isset($input['position'])) {
            $employee->setPosition($input['position']);
        }
        if (isset($input['salary'])) {
            $employee->setSalary((float)$input['salary']);
        }

        $savedEmployee = $this->employeeRepository->save($employee);

        return [
            'entity_id' => (int)$savedEmployee->getId(),
            'name' => $savedEmployee->getName(),
            'email' => $savedEmployee->getEmail(),
            'department' => $savedEmployee->getDepartment(),
            'position' => $savedEmployee->getPosition(),
            'salary' => (float)$savedEmployee->getSalary()
        ];
    }
}
