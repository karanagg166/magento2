<?php

namespace Kunal\LowStockNotification\Observer;

use Kunal\LowStockNotification\Logger\Logger;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Listens for the custom "kunal_product_qty_below_threshold" event and notifies
 * the store owner / warehouse manager about the low-inventory product by email.
 */
class LowInventoryNotificationObserver implements ObserverInterface
{
    public const XML_PATH_RECIPIENT_NAME  = 'lowstock/notification/recipient_name';
    public const XML_PATH_RECIPIENT_EMAIL = 'lowstock/notification/recipient_email';
    public const XML_PATH_SENDER          = 'lowstock/notification/sender';

    public const EMAIL_TEMPLATE = 'lowstock_notification_email_template';

    private ScopeConfigInterface $scopeConfig;
    private TransportBuilder $transportBuilder;
    private StateInterface $inlineTranslation;
    private StoreManagerInterface $storeManager;
    private Logger $logger;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        TransportBuilder $transportBuilder,
        StateInterface $inlineTranslation,
        StoreManagerInterface $storeManager,
        Logger $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    public function execute(Observer $observer)
    {
        $product   = $observer->getEvent()->getData('product');
        $qty       = (float) $observer->getEvent()->getData('qty');
        $threshold = (float) $observer->getEvent()->getData('threshold');

        if (!$product) {
            return;
        }

        $store = $this->storeManager->getStore();

        $recipientEmail = $this->getRecipientEmail($store->getId());
        if (!$recipientEmail) {
            $this->logger->error(
                'Low-stock notification not sent: no recipient email configured and no general contact fallback.'
            );
            return;
        }

        $recipientName = $this->scopeConfig->getValue(
            self::XML_PATH_RECIPIENT_NAME,
            ScopeInterface::SCOPE_STORE,
            $store->getId()
        ) ?: 'Warehouse Manager';

        $sender = $this->scopeConfig->getValue(
            self::XML_PATH_SENDER,
            ScopeInterface::SCOPE_STORE,
            $store->getId()
        ) ?: 'general';

        $this->inlineTranslation->suspend();
        try {
            $transport = $this->transportBuilder
                ->setTemplateIdentifier(self::EMAIL_TEMPLATE)
                ->setTemplateOptions([
                    'area'  => \Magento\Framework\App\Area::AREA_ADMINHTML,
                    'store' => $store->getId(),
                ])
                ->setTemplateVars([
                    'product_name'   => $product->getName(),
                    'product_sku'    => $product->getSku(),
                    'product_id'     => $product->getId(),
                    'qty'            => $qty,
                    'threshold'      => $threshold,
                    'store_name'     => $store->getFrontendName(),
                    'recipient_name' => $recipientName,
                ])
                ->setFromByScope($sender, $store->getId())
                ->addTo($recipientEmail, $recipientName)
                ->getTransport();

            $transport->sendMessage();

            $this->logger->info(sprintf(
                'Low-stock notification sent to %s <%s> for "%s" (SKU: %s), qty %s below threshold %s.',
                $recipientName,
                $recipientEmail,
                $product->getName(),
                $product->getSku(),
                $qty,
                $threshold
            ));
        } catch (\Throwable $e) {
            $this->logger->error('Failed to send low-stock notification: ' . $e->getMessage());
        } finally {
            $this->inlineTranslation->resume();
        }
    }

    /**
     * Configured recipient, falling back to the store's General Contact address.
     */
    private function getRecipientEmail($storeId): ?string
    {
        $email = $this->scopeConfig->getValue(
            self::XML_PATH_RECIPIENT_EMAIL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (!$email) {
            $email = $this->scopeConfig->getValue(
                'trans_email/ident_general/email',
                ScopeInterface::SCOPE_STORE,
                $storeId
            );
        }

        return $email ?: null;
    }
}
