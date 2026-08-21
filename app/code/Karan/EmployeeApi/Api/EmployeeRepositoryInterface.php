<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Api;

use Karan\EmployeeApi\Api\Data\EmployeeInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface EmployeeRepositoryInterface
{
    /**
     * Save Employee
     *
     * @param EmployeeInterface $employee
     * @return EmployeeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(EmployeeInterface $employee);

    /**
     * Get Employee by ID
     *
     * @param int $entityId
     * @return EmployeeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($entityId);

    /**
     * Get Employee list matching search criteria
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return \Karan\EmployeeApi\Api\Data\EmployeeSearchResultsInterface
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null);

    /**
     * Delete Employee
     *
     * @param EmployeeInterface $employee
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(EmployeeInterface $employee);

    /**
     * Delete Employee by ID
     *
     * @param int $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
