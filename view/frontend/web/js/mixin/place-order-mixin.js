/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
*/

define([
    'mage/utils/wrapper',
    'ExpertSender_Ecdp/js/action/set-customer-consents-on-payment'
], function (wrapper, setCustomerConsentsOnPaymentAction) {
    'use strict';

    return function (placeOrder) {
        return wrapper.wrap(placeOrder, function (originalAction, paymentData, messageContainer) {
            setCustomerConsentsOnPaymentAction(paymentData);
            return originalAction(paymentData, messageContainer);
        })
    }
});
