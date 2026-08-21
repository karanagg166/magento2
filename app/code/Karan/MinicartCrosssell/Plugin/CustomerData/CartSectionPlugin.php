<?php
/**
 * Copyright © Karan. All rights reserved.
 */
declare(strict_types=1);

namespace Karan\MinicartCrosssell\Plugin\CustomerData;

use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Checkout\CustomerData\Cart as CartSection;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\UrlInterface;

class CartSectionPlugin
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ImageHelper
     */
    private $imageHelper;

    /**
     * @var PricingHelper
     */
    private $pricingHelper;

    /**
     * @var FormKey
     */
    private $formKey;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param CheckoutSession $checkoutSession
     * @param ImageHelper $imageHelper
     * @param PricingHelper $pricingHelper
     * @param FormKey $formKey
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ImageHelper $imageHelper,
        PricingHelper $pricingHelper,
        FormKey $formKey,
        UrlInterface $urlBuilder
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->imageHelper = $imageHelper;
        $this->pricingHelper = $pricingHelper;
        $this->formKey = $formKey;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Inject cross-sell products (max 2) into minicart section data
     *
     * @param CartSection $subject
     * @param array $result
     * @return array
     */
    public function afterGetSectionData(CartSection $subject, array $result): array
    {
        $quote = $this->checkoutSession->getQuote();
        $crossSellItems = [];
        $addedIds = [];

        if ($quote && $quote->getId()) {
            foreach ($quote->getAllVisibleItems() as $item) {
                $product = $item->getProduct();
                if (!$product) {
                    continue;
                }

                $crossSellProducts = $product->getCrossSellProducts();
                if ($crossSellProducts) {
                    foreach ($crossSellProducts as $crossSellProduct) {
                        $id = (int)$crossSellProduct->getId();
                        if (isset($addedIds[$id])) {
                            continue;
                        }

                        $addedIds[$id] = true;
                        $image = $this->imageHelper->init($crossSellProduct, 'mini_cart_product_thumbnail')
                            ->getUrl();
                        $crossSellItems[] = [
                            'id' => $id,
                            'name' => $crossSellProduct->getName(),
                            'url' => $crossSellProduct->getProductUrl(),
                            'image' => $image,
                            'price' => $this->pricingHelper->currency($crossSellProduct->getFinalPrice(), true, false),
                            'add_to_cart_url' => $this->urlBuilder->getUrl('checkout/cart/add', [
                                'product' => $id,
                                'form_key' => $this->formKey->getFormKey()
                            ])
                        ];

                        if (count($crossSellItems) >= 2) {
                            break 2;
                        }
                    }
                }
            }
        }

        $result['crosssell_products'] = $crossSellItems;
        return $result;
    }
}
