<?php

namespace Karan\CategoryPermissions\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class WebsiteOptions implements OptionSourceInterface
{
    private StoreManagerInterface $storeManager;

    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    public function toOptionArray(): array
    {
        $options = [];

        foreach ($this->storeManager->getWebsites() as $website) {
            $options[] = [
                'value' => $website->getId(),
                'label' => $website->getName()
            ];
        }

        return $options;
    }
}
