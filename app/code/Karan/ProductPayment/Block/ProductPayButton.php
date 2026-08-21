<?php

declare(strict_types=1);

namespace Karan\ProductPayment\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session as CustomerSession;
use StripeIntegration\Payments\Model\Config as StripeConfig;

#[\AllowDynamicProperties]
class ProductPayButton extends Template
{
    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var CustomerSession
     */
    private CustomerSession $customerSession;

    /**
     * @var StripeConfig
     */
    private StripeConfig $stripeConfig;

    /**
     * @param Template\Context $context
     * @param Registry $registry
     * @param CustomerSession $customerSession
     * @param StripeConfig $stripeConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        CustomerSession $customerSession,
        StripeConfig $stripeConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry       = $registry;
        $this->customerSession = $customerSession;
        $this->stripeConfig   = $stripeConfig;
    }

    /**
     * Check whether Stripe is initialized and usable
     */
    public function isEnabled(): bool
    {
        return (bool) $this->stripeConfig->initStripe();
    }

    /**
     * Stripe publishable key
     */
    public function getPublishableKey(): string
    {
        return (string) $this->stripeConfig->getPublishableKey();
    }

    /**
     * Current product ID from registry
     */
    public function getProductId(): int
    {
        $product = $this->registry->registry('product');
        return $product ?  (int) $product->getId() : 0;
    }

    /**
     * Current product name
     */
    public function getProductName(): string
    {
        $product = $this->registry->registry('product');
        return $product ?  (string) $product->getName() : '';
    }

    /**
     * Create-intent AJAX URL
     */
    public function getCreateIntentUrl(): string
    {
        return $this->getUrl('karan-pay/payment/createIntent');
    }

    /**
     * Prefilled shipping address from logged-in customer's default shipping address.
     * Returns an array safe for JSON encoding.
     */
    public function getPrefillAddress(): array
    {
        $data = [
            'firstname' => '',
            'lastname'  => '',
            'email'     => '',
            'street'    => '',
            'city'      => '',
            'region'    => '',
            'postcode'  => '',
            'country'   => 'IN',
            'telephone' => '',
        ];

        if (!$this->customerSession->isLoggedIn()) {
            return $data;
        }

        $customer = $this->customerSession->getCustomer();
        $data['email'] = (string) $customer->getEmail();

        $address = $customer->getDefaultShippingAddress();
        if (!$address) {
            $data['firstname'] = (string) $customer->getFirstname();
            $data['lastname']  = (string) $customer->getLastname();
            return $data;
        }

        $streets = $address->getStreet();

        $data['firstname'] = (string) $address->getFirstname();
        $data['lastname']  = (string) $address->getLastname();
        $data['street']    = isset($streets[0]) ?  (string) $streets[0] : '';
        $data['city']      = (string) $address->getCity();
        $data['region']    = (string) $address->getRegion();
        $data['postcode']  = (string) $address->getPostcode();
        $data['country']   = (string) $address->getCountryId();
        $data['telephone'] = (string) $address->getTelephone();

        return $data;
    }

    /**
     * JSON-encoded prefill data for use in the template script tag
     */
    public function getPrefillAddressJson(): string
    {
        return json_encode($this->getPrefillAddress(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
    }
}
