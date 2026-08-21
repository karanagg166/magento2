<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\EmployeeApi\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface EmployeeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Employee list
     *
     * @return \Karan\EmployeeApi\Api\Data\EmployeeInterface[]
     */
    public function getItems();

    /**
     * Set Employee list
     *
     * @param \Karan\EmployeeApi\Api\Data\EmployeeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
