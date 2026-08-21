<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Api;

use Karan\AnnouncementApi\Api\Data\AnnouncementInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

interface AnnouncementRepositoryInterface
{
    /**
     * Save Announcement
     *
     * @param AnnouncementInterface $announcement
     * @return AnnouncementInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(AnnouncementInterface $announcement);

    /**
     * Get Announcement by ID
     *
     * @param int $entityId
     * @return AnnouncementInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($entityId);

    /**
     * Get Announcements list matching search criteria
     *
     * @param SearchCriteriaInterface|null $searchCriteria
     * @return \Karan\AnnouncementApi\Api\Data\AnnouncementSearchResultsInterface
     */
    public function getList(?SearchCriteriaInterface $searchCriteria = null);

    /**
     * Delete Announcement
     *
     * @param AnnouncementInterface $announcement
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(AnnouncementInterface $announcement);

    /**
     * Delete Announcement by ID
     *
     * @param int $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
