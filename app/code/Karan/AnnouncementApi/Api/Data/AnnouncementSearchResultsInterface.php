<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\AnnouncementApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface AnnouncementSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Announcements list
     *
     * @return \Karan\AnnouncementApi\Api\Data\AnnouncementInterface[]
     */
    public function getItems();

    /**
     * Set Announcements list
     *
     * @param \Karan\AnnouncementApi\Api\Data\AnnouncementInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
