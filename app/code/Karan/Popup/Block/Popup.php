<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Popup\Block;

use Karan\Popup\Model\ResourceModel\Popup\Collection;
use Karan\Popup\Model\ResourceModel\Popup\CollectionFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Popup extends Template
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get active popups from database
     *
     * @return Collection
     */
    public function getActivePopups(): Collection
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('is_active', 1);
        return $collection;
    }
}
