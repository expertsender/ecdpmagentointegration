/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
 */

define([
    'Magento_Checkout/js/checkout-data'
], function (
    checkoutData
) {
    'use strict';

    return function (paymentData) {
        if (undefined === paymentData['extension_attributes']) {
            paymentData['extension_attributes'] = {};
        }

        let customerConsents = [];

        checkoutData.getCustomerConsents().forEach(function (consent) {
            customerConsents.push(consent.value);
        })

        paymentData['extension_attributes']['customer_consents'] = customerConsents;
    }
});
