<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model\Resolver;

use Karan\EmployeeApi\Api\EmployeeRepositoryInterface;
use Karan\EmployeeApi\Model\EmployeeFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CreateEmployee implements ResolverInterface
{
    /**
     * @var EmployeeRepositoryInterface
     */
    private $employeeRepository;

    /**
     * @var EmployeeFactory
     */
    private $employeeFactory;

    /**
     * @param EmployeeRepositoryInterface $employeeRepository
     * @param EmployeeFactory $employeeFactory
     */
    public function __construct(
        EmployeeRepositoryInterface $employeeRepository,
        EmployeeFactory $employeeFactory
    ) {
        $this->employeeRepository = $employeeRepository;
        $this->employeeFactory = $employeeFactory;
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
        if (!isset($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('Invalid input argument'));
        }

        $input = $args['input'];
        $employee = $this->employeeFactory->create();
        $employee->setName($input['name'] ?? '');
        $employee->setEmail($input['email'] ?? '');
        $employee->setDepartment($input['department'] ?? null);
        $employee->setPosition($input['position'] ?? null);
        $employee->setSalary(isset($input['salary']) ?  (float)$input['salary'] : null);

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
