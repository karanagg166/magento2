<?php

declare(strict_types=1);

namespace Karan\ProductPayment\Controller\Payment;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteManagement;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Model\OrderFactory;
use StripeIntegration\Payments\Model\Config as StripeConfig;
use StripeIntegration\Payments\Helper\Generic as StripeHelper;

#[\AllowDynamicProperties]
class Confirm implements HttpPostActionInterface
{
    public function __construct(
        private readonly RequestInterface        $request,
        private readonly JsonFactory             $jsonFactory,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly QuoteManagement         $quoteManagement,
        private readonly CustomerSession         $customerSession,
        private readonly OrderFactory            $orderFactory,
        private readonly StripeConfig            $stripeConfig,
        private readonly StripeHelper            $stripeHelper,
    ) {}

    public function execute()
    {
        $result = $this->jsonFactory->create();

        try {
            $body            = json_decode((string) $this->request->getContent(), true) ?? [];
            $paymentIntentId = trim((string) ($body['payment_intent_id'] ?? ''));

            if (empty($paymentIntentId)) {
                throw new LocalizedException(__('Payment Intent ID is required.'));
            }

            // ── 1. Verify payment intent with Stripe ──────────────────────
            $stripeClient = $this->stripeConfig->getStripeClient();
            if (!$stripeClient) {
                throw new LocalizedException(__('Stripe is not configured.'));
            }

            $intent = $stripeClient->paymentIntents->retrieve($paymentIntentId);
            if ($intent->status !== 'succeeded' && $intent->status !== 'processing') {
                throw new LocalizedException(
                    __('Payment has not succeeded yet. Status: %1', $intent->status)
                );
            }

            // ── 2. Retrieve the quote we built in CreateIntent ────────────
            $quoteId = (int) ($this->customerSession->getKaranProductPayQuoteId()
                           ?? ($body['quote_id'] ?? 0));

            if (!$quoteId) {
                throw new LocalizedException(__('Session expired. Could not find cart. Please try again.'));
            }

            $quote = $this->cartRepository->get($quoteId);

            // ── 3. Attach the Stripe payment method to the quote payment ──
            $payment = $quote->getPayment();
            $payment->setMethod('stripe_payments');
            $payment->setAdditionalInformation('payment_intent_id', $paymentIntentId);
            $payment->setAdditionalInformation('payment_location', 'product_page_buy_now');

            $quote->setPaymentMethod('stripe_payments');
            $quote->setTotalsCollectedFlag(false)->collectTotals();
            $this->cartRepository->save($quote);

            // ── 4. Place the order ────────────────────────────────────────
            $orderId = $this->quoteManagement->placeOrder($quoteId);
            $order   = $this->orderFactory->create()->load($orderId);

            // Clear session keys
            $this->customerSession->unsKaranProductPayQuoteId();
            $this->customerSession->unsKaranProductPayIntentId();

            return $result->setData([
                'success'      => true,
                'order_id'     => $order->getIncrementId(),
                'redirect_url' => 'checkout/onepage/success',
            ]);

        } catch (\Exception $e) {
            return $result->setData([
                'success' => false,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}
