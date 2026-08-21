<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Model;

use Karan\EmployeeApi\Api\Data\EmployeeInterface;
use Karan\EmployeeApi\Api\Data\EmployeeSearchResultsInterfaceFactory;
use Karan\EmployeeApi\Api\EmployeeRepositoryInterface;
use Karan\EmployeeApi\Model\ResourceModel\Employee as EmployeeResource;
use Karan\EmployeeApi\Model\ResourceModel\Employee\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    /**
     * @var EmployeeResource
     */
    private $resource;

    /**
     * @var EmployeeFactory
     */
    private $employeeFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var EmployeeSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param EmployeeResource $resource
     * @param EmployeeFactory $employeeFactory
     * @param CollectionFactory $collectionFactory
     * @param EmployeeSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        EmployeeResource $resource,
        EmployeeFactory $employeeFactory,
        CollectionFactory $collectionFactory,
        EmployeeSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->employeeFactory = $employeeFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(EmployeeInterface $employee)
    {
        try {
            $this->resource->save($employee);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save employee: %1', $exception->getMessage())
            );
        }
        return $employee;
    }

    /**
     * @inheritdoc
     */
    public function getById($entityId)
    {
        $employee = $this->employeeFactory->create();
        $this->resource->load($employee, $entityId);
        if (!$employee->getId()) {
            throw new NoSuchEntityException(__('Employee with ID "%1" does not exist.', $entityId));
        }
        return $employee;
    }

    /**
     * @inheritdoc
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null)
    {
        $collection = $this->collectionFactory->create();

        if ($searchCriteria !== null) {
            $this->collectionProcessor->process($searchCriteria, $collection);
        }

        $searchResults = $this->searchResultsFactory->create();
        if ($searchCriteria !== null) {
            $searchResults->setSearchCriteria($searchCriteria);
        }
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function delete(EmployeeInterface $employee)
    {
        try {
            $this->resource->delete($employee);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete employee: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }
}
