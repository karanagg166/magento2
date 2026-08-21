<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\Mod9\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class PageColor extends Template
{
    private const XML_PATH_PAGE_COLOR = 'mod9_mod16/mod16/color';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

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
     * Get configured page background color
     *
     * @return string
     */
    public function getPageColor(): string
    {
        $color = (string) $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_COLOR,
            ScopeInterface::SCOPE_STORE
        );

        return trim($color);
    }
}
