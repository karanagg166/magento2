<?php

declare(strict_types=1);

namespace Karan\ProductPayment\Controller\Payment;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Directory\Model\RegionFactory;
use StripeIntegration\Payments\Model\Config as StripeConfig;
use StripeIntegration\Payments\Helper\Generic as StripeHelper;

#[\AllowDynamicProperties]
class CreateIntent implements HttpPostActionInterface
{
    public function __construct(
        private readonly RequestInterface            $request,
        private readonly JsonFactory                 $jsonFactory,
        private readonly ProductRepositoryInterface  $productRepository,
        private readonly QuoteFactory                $quoteFactory,
        private readonly QuoteManagement             $quoteManagement,
        private readonly CartRepositoryInterface     $cartRepository,
        private readonly StoreManagerInterface       $storeManager,
        private readonly CustomerSession             $customerSession,
        private readonly RegionFactory               $regionFactory,
        private readonly StripeConfig                $stripeConfig,
        private readonly StripeHelper                $stripeHelper,
    ) {}

    public function execute()
    {
        $result = $this->jsonFactory->create();

        try {
            // ── 1. Decode payload ──────────────────────────────────────────
            $body = json_decode((string) $this->request->getContent(), true) ?? [];

            $productId = (int) ($body['product_id'] ?? 0);
            $qty       = max(1, (int) ($body['qty'] ?? 1));
            $shipping  = $body['shipping'] ?? [];

            if (!$productId) {
                throw new LocalizedException(__('Product ID is required.'));
            }

            // ── 2. Load product ───────────────────────────────────────────
            $product = $this->productRepository->getById(
                $productId,
                false,
                $this->storeManager->getStore()->getId()
            );

            // ── 3. Create / reuse a quote ─────────────────────────────────
            $store = $this->storeManager->getStore();
            $quote = $this->quoteFactory->create();
            $quote->setStore($store);
            $quote->setStoreId($store->getId());
            $quote->setCurrencyCode($store->getCurrentCurrencyCode());

            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomerDataObject();
                $quote->assignCustomer($customer);
            } else {
                $quote->setCustomerIsGuest(true);
                $quote->setCustomerEmail($shipping['email'] ?? 'guest@example.com');
            }

            // ── 4. Add product to quote ───────────────────────────────────
            $quote->addProduct($product, $qty);

            // ── 5. Set shipping address ───────────────────────────────────
            $firstname = trim((string) ($shipping['firstname'] ?? ''));
            $lastname  = trim((string) ($shipping['lastname'] ?? ''));
            $street    = trim((string) ($shipping['street'] ?? ''));
            $city      = trim((string) ($shipping['city'] ?? ''));
            $postcode  = trim((string) ($shipping['postcode'] ?? ''));
            $country   = trim((string) ($shipping['country'] ?? 'IN'));
            $telephone = trim((string) ($shipping['telephone'] ?? '0000000000'));
            $regionStr = trim((string) ($shipping['region'] ?? ''));

            // Resolve region ID
            $regionId = 0;
            if ($regionStr) {
                $region = $this->regionFactory->create()->loadByName($regionStr, $country);
                if ($region->getId()) {
                    $regionId = (int) $region->getId();
                }
            }

            $addressData = [
                'firstname'  => $firstname ?: 'Guest',
                'lastname'   => $lastname  ?: 'User',
                'street'     => $street    ?: 'N/A',
                'city'       => $city      ?: 'N/A',
                'country_id' => $country,
                'region'     => $regionStr,
                'region_id'  => $regionId,
                'postcode'   => $postcode  ?: '000000',
                'telephone'  => $telephone,
                'save_in_address_book' => 0,
            ];

            $shippingAddress = $quote->getShippingAddress();
            $shippingAddress->addData($addressData);

            // Collect shipping rates and pick the cheapest flat-rate / free
            $shippingAddress->setCollectShippingRates(true)->collectShippingRates();
            $rates = $shippingAddress->getAllShippingRates();

            $selectedRate = null;
            foreach ($rates as $rate) {
                if ($selectedRate === null || $rate->getPrice() < $selectedRate->getPrice()) {
                    $selectedRate = $rate;
                }
            }

            if ($selectedRate) {
                $shippingAddress->setShippingMethod($selectedRate->getCode());
            }

            // Billing mirrors shipping for simplicity
            $quote->getBillingAddress()->addData($addressData);
            if (!$this->customerSession->isLoggedIn()) {
                $quote->getBillingAddress()->setEmail($shipping['email'] ?? 'guest@example.com');
            }

            // ── 6. Set payment method to stripe ──────────────────────────
            $quote->setPaymentMethod('stripe_payments');
            $quote->getPayment()->importData(['method' => 'stripe_payments']);

            // ── 7. Collect totals & save ──────────────────────────────────
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $this->cartRepository->save($quote);

            // ── 8. Create Stripe PaymentIntent ────────────────────────────
            $currency = strtolower($quote->getQuoteCurrencyCode() ?: $store->getCurrentCurrencyCode());
            $amount   = $quote->getGrandTotal();
            $cents    = $this->stripeHelper->isZeroDecimal($currency) ? 1 : 100;
            $amountInCents = (int) round($amount * $cents);

            $stripeClient = $this->stripeConfig->getStripeClient();
            if (!$stripeClient) {
                throw new LocalizedException(__('Stripe is not configured. Please contact the store administrator.'));
            }

            $description = sprintf(
                'Product page order: %s x%d',
                $product->getName(),
                $qty
            );

            $intentData = [
                'amount'               => $amountInCents,
                'currency'             => $currency,
                'description'          => $description,
                'capture_method'       => 'automatic',
                'metadata'             => [
                    'module'     => 'Karan_ProductPayment',
                    'product_id' => $productId,
                    'qty'        => $qty,
                    'quote_id'   => $quote->getId(),
                ],
            ];

            $paymentIntent = $stripeClient->paymentIntents->create($intentData);

            // Persist quote ID → payment intent mapping in session for Confirm controller
            $this->customerSession->setKaranProductPayQuoteId($quote->getId());
            $this->customerSession->setKaranProductPayIntentId($paymentIntent->id);

            return $result->setData([
                'success'       => true,
                'client_secret' => $paymentIntent->client_secret,
                'amount'        => $amount,
                'currency'      => strtoupper($currency),
                'quote_id'      => $quote->getId(),
            ]);

        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
