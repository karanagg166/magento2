<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Model;

use Karan\AnnouncementApi\Api\AnnouncementRepositoryInterface;
use Karan\AnnouncementApi\Api\Data\AnnouncementInterface;
use Karan\AnnouncementApi\Api\Data\AnnouncementSearchResultsInterfaceFactory;
use Karan\AnnouncementApi\Model\ResourceModel\Announcement as AnnouncementResource;
use Karan\AnnouncementApi\Model\ResourceModel\Announcement\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class AnnouncementRepository implements AnnouncementRepositoryInterface
{
    /**
     * @var AnnouncementResource
     */
    private $resource;

    /**
     * @var AnnouncementFactory
     */
    private $announcementFactory;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var AnnouncementSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @param AnnouncementResource $resource
     * @param AnnouncementFactory $announcementFactory
     * @param CollectionFactory $collectionFactory
     * @param AnnouncementSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        AnnouncementResource $resource,
        AnnouncementFactory $announcementFactory,
        CollectionFactory $collectionFactory,
        AnnouncementSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->announcementFactory = $announcementFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritdoc
     */
    public function save(AnnouncementInterface $announcement)
    {
        try {
            $this->resource->save($announcement);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save announcement: %1', $exception->getMessage())
            );
        }
        return $announcement;
    }

    /**
     * @inheritdoc
     */
    public function getById($entityId)
    {
        $announcement = $this->announcementFactory->create();
        $this->resource->load($announcement, $entityId);
        if (!$announcement->getId()) {
            throw new NoSuchEntityException(__('Announcement with ID "%1" does not exist.', $entityId));
        }
        return $announcement;
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
    public function delete(AnnouncementInterface $announcement)
    {
        try {
            $this->resource->delete($announcement);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete announcement: %1', $exception->getMessage())
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
