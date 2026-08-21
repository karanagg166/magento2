<?php

namespace Karan\Mod9\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\ScopeInterface;

class Index implements HttpGetActionInterface
{
    private const XML_PATH_ENABLE = 'mod9_general/general/enable';
    private const XML_PATH_TEXT   = 'mod9_general/general/text_to_display';
    private const XML_COLOR_PATH  = 'mod9_mod16/mod16/color';


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
            self::XML_PATH_ENABLE
        );

        if (!$isEnabled) {
            return $result->setContents('');
        }

        $text = (string) $this->scopeConfig->getValue(
            self::XML_PATH_TEXT
        );

        $color = (string) $this->scopeConfig->getValue(
            self::XML_COLOR_PATH
        );

        // Fallback colour
        if (empty($color)) {
            $color = '#ffffff';
        }

         $html = sprintf(
            '<!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Mod9</title>
            </head>
            <body style="margin:0; padding:40px; background-color:%s; color:#333333; font-family: Arial, sans-serif; font-size: 24px;">
                %s
            </body>
            </html>',
            htmlspecialchars($color, ENT_QUOTES, 'UTF-8'),
            nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'))
        );

        return $result->setContents($html);
    }
}
