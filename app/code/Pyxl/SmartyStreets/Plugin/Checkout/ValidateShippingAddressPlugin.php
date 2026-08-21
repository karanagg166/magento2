<?php
/**
 * Pyxl_SmartyStreets
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright  Copyright (c) 2018 Pyxl, Inc.
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

namespace Pyxl\SmartyStreets\Plugin\Checkout;

use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Framework\Exception\InputException;
use Pyxl\SmartyStreets\Model\Validator;
use Pyxl\SmartyStreets\Helper\Config;
use Magento\Customer\Api\Data\AddressInterfaceFactory;
use Magento\Quote\Api\CartRepositoryInterface;

class ValidateShippingAddressPlugin
{
    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var AddressInterfaceFactory
     */
    private $addressDataFactory;

    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * ValidateShippingAddressPlugin constructor.
     *
     * @param Validator $validator
     * @param Config $config
     * @param AddressInterfaceFactory $addressDataFactory
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        Validator $validator,
        Config $config,
        AddressInterfaceFactory $addressDataFactory,
        CartRepositoryInterface $cartRepository
    ) {
        $this->validator = $validator;
        $this->config = $config;
        $this->addressDataFactory = $addressDataFactory;
        $this->cartRepository = $cartRepository;
    }

    /**
     * Validate shipping address via SmartyStreets before saving.
     *
     * @param ShippingInformationManagementInterface $subject
     * @param int $cartId
     * @param ShippingInformationInterface $addressInformation
     * @return void
     * @throws InputException
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        if (!$this->config->isModuleEnabled()) {
            return;
        }

        $quoteAddress = $addressInformation->getShippingAddress();

        // Build a CustomerAddressInterface from the quote address for the validator
        /** @var \Magento\Customer\Api\Data\AddressInterface $address */
        $address = $this->addressDataFactory->create();
        $address->setStreet($quoteAddress->getStreet());
        $address->setCity($quoteAddress->getCity());
        $address->setPostcode($quoteAddress->getPostcode());
        $address->setCountryId($quoteAddress->getCountryId());

        $region = $quoteAddress->getRegion();
        if ($region) {
            $regionData = $address->getRegion() ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->create(\Magento\Customer\Api\Data\RegionInterface::class);
            $regionData->setRegionCode($quoteAddress->getRegionCode());
            $regionData->setRegionId($quoteAddress->getRegionId());
            $regionData->setRegion($region);
            $address->setRegion($regionData);
        }

        $results = $this->validator->validate($address);

        if (!$results['valid']) {
            throw new InputException($results['message']);
        }
    }
}
