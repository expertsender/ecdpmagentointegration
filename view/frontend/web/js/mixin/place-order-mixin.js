/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
*/

define([
    'mage/utils/wrapper',
    'Endora_ExpertSenderCdp/js/action/set-customer-consents-on-payment'
], function (wrapper, setCustomerConsentsOnPaymentAction) {
    'use strict';

    return function (placeOrder) {
        return wrapper.wrap(placeOrder, function (originalAction, paymentData, messageContainer) {
            setCustomerConsentsOnPaymentAction(paymentData);
            return originalAction(paymentData, messageContainer);
        })
    }
});
