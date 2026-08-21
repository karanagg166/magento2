define([
    'ko',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator',
    'mage/translate'
], function (ko, Component, _, stepNavigator, $t) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Karan_CustomCheckoutStep/contact-step'
        },
        isVisible: ko.observable(true),
        stepCode: 'contact',
        stepTitle: $t('Contact Step'),

        /**
         * @return {exports}
         */
        initialize: function () {
            this._super();

            // Register step in stepNavigator
            stepNavigator.registerStep(
                this.stepCode,
                null,
                this.stepTitle,
                this.isVisible,
                _.bind(this.navigate, this),
                5
            );

            return this;
        },

        /**
         * Navigation handler for stepNavigator
         */
        navigate: function () {
            this.isVisible(true);
        },

        /**
         * Navigate to next step in checkout
         */
        navigateToNextStep: function () {
            stepNavigator.next();
        }
    });
});
