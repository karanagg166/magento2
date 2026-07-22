<?php

namespace Kunal\Mod9\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;

class Index implements HttpGetActionInterface
{
    private const XML_PATH_ENABLE = 'mod9_general/general/enable';
    private const XML_PATH_TEXT   = 'mod9_general/general/text_to_display';


    private ScopeConfigInterface $scopeConfig;
    private ResultFactory $resultFactory;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResultFactory $resultFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resultFactory = $resultFactory;
    }

    public function execute()
    {
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);

        $isEnabled = $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE
        );

        if (!$isEnabled) {
            $result->setContents('');
            return $result;
        }

        $text = (string) $this->scopeConfig->getValue(
            self::XML_PATH_TEXT,
            ScopeInterface::SCOPE_STORE
        );

        $result->setContents(nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8')));

        return $result;
    }
}
