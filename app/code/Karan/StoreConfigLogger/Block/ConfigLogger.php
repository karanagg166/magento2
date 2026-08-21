<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\StoreConfigLogger\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

class ConfigLogger extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get store configuration data to pass to AMD JS component
     *
     * @return array
     */
    public function getConfigData(): array
    {
        return [
            'salesEmail' => (string)$this->scopeConfig->getValue(
                'trans_email/ident_sales/email',
                ScopeInterface::SCOPE_STORE
            ),
            'salesName' => (string)$this->scopeConfig->getValue(
                'trans_email/ident_sales/name',
                ScopeInterface::SCOPE_STORE
            ),
            'storeName' => (string)$this->scopeConfig->getValue(
                'general/store_information/name',
                ScopeInterface::SCOPE_STORE
            ),
            'storePhone' => (string)$this->scopeConfig->getValue(
                'general/store_information/phone',
                ScopeInterface::SCOPE_STORE
            ),
            'paymentMethods' => [
                'checkmo' => [
                    'active' => (bool)$this->scopeConfig->getValue(
                        'payment/checkmo/active',
                        ScopeInterface::SCOPE_STORE
                    ),
                    'title' => (string)$this->scopeConfig->getValue(
                        'payment/checkmo/title',
                        ScopeInterface::SCOPE_STORE
                    )
                ],
                'banktransfer' => [
                    'active' => (bool)$this->scopeConfig->getValue(
                        'payment/banktransfer/active',
                        ScopeInterface::SCOPE_STORE
                    ),
                    'title' => (string)$this->scopeConfig->getValue(
                        'payment/banktransfer/title',
                        ScopeInterface::SCOPE_STORE
                    )
                ]
            ]
        ];
    }
}
