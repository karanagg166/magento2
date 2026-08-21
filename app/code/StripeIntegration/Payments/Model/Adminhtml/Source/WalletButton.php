<?php

namespace StripeIntegration\Payments\Model\Adminhtml\Source;

#[\AllowDynamicProperties]
class WalletButton
{
    public function toOptionArray()
    {
        return [
            [
                'value' => "product_page",
                'label' => __('Product pages')
            ],
            [
                'value' => "minicart",
                'label' => __('Minicart')
            ],
            [
                'value' => "shopping_cart_page",
                'label' => __('Shopping cart page')
            ],
            [
                'value' => "checkout_page",
                'label' => __('Checkout page (top section)')
            ]
        ];
    }
}
