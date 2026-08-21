define([
    'jquery'
], function ($) {
    'use strict';

    return function (configData) {
        console.group('=== User Story #24: Store Configuration Values (AMD RequireJS) ===');
        console.log('Sales Email:', configData.salesEmail);
        console.log('Sales Sender Name:', configData.salesName);
        console.log('Store Name:', configData.storeName);
        console.log('Store Phone:', configData.storePhone);
        console.log('Payment Methods:', configData.paymentMethods);
        console.log('Full Config Object:', configData);
        console.groupEnd();
    };
});
