<?php

namespace Karan\Mod1\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Reads the admin configuration that decides when custom_order_placement fires.
 */
class OrderPlacementConfig
{
    public const XML_PATH_ENABLED         = 'mod1/order_placement/enabled';
    public const XML_PATH_CUSTOMER_GROUPS = 'mod1/order_placement/customer_groups';

    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * IDs of the designated customer groups, as configured in the multiselect.
     *
     * @return int[]
     */
    public function getDesignatedGroupIds($storeId = null): array
    {
        $value = $this->scopeConfig->getValue(
            self::XML_PATH_CUSTOMER_GROUPS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if ($value === null || $value === '') {
            return [];
        }

        // Stored comma-separated by the multiselect backend model.
        $ids = is_array($value) ?  $value : explode(',', (string) $value);

        // Filter before casting: group 0 is NOT LOGGED IN, so a stray non-numeric
        // entry must not become a valid "0" through intval().
        $ids = array_filter(array_map('trim', $ids), static fn ($id) => is_numeric($id));

        return array_values(array_map('intval', $ids));
    }

    /**
     * Whether orders from this group should trigger the custom event.
     */
    public function isDesignatedGroup(int $customerGroupId, $storeId = null): bool
    {
        return in_array($customerGroupId, $this->getDesignatedGroupIds($storeId), true);
    }
}
