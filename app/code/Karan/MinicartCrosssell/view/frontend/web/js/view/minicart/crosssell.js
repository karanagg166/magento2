/**
 * Copyright © Karan. All rights reserved.
 */
define([
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function (Component, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Karan_MinicartCrosssell/minicart/crosssell'
        },

        /**
         * @override
         */
        initialize: function () {
            this._super();
            this.cart = customerData.get('cart');
        },

        /**
         * Get first two cross-sell products from cart data
         *
         * @returns {Array}
         */
        getCrosssellProducts: function () {
            var cartData = this.cart();
            if (cartData && cartData.crosssell_products && cartData.crosssell_products.length > 0) {
                return cartData.crosssell_products.slice(0, 2);
            }
            return [];
        },

        /**
         * Check if cross-sell products exist
         *
         * @returns {Boolean}
         */
        hasCrosssellProducts: function () {
            return this.getCrosssellProducts().length > 0;
        }
    });
});
