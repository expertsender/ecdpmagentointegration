/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package Endora_ExpertSenderCdp
*/

define([
    'uiComponent',
    'ko',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/checkout-data'
], function (Component, ko, customerData, checkoutData) {
    'use strict';

    return Component.extend({
        defaults: {
            customerConsents: ko.observableArray([])
        },

        initialize: function () {
            this._super();
            let self = this;
            let formElements = this.getFormElements();

            formElements.forEach(element => {
                self.customerConsents.push({
                    value: element.value,
                    content: element.content,
                    checked: ko.observable(false)
                });

                self.customerConsents.slice(-1)[0].checked.subscribe(
                    function () {
                        self.updateCheckoutDataConsents()
                    }
                );
            });

            self.updateCheckoutDataConsents();
        },

        getConfig: function () {
            return window.checkoutConfig.expertSenderCustomerConsents;
        },

        getFormElements: function () {
            return this.getConfig().formElements;
        },

        getTextBeforeConsents: function () {
            return this.getConfig().textBeforeConsents;
        },

        selectConsent: function (element) {
            element.checked(!element.checked());
            return true;
        },

        updateCheckoutDataConsents: function () {
            let consents = this.customerConsents().filter(
                (consent) => consent.checked() === true
            );
            
            consents.forEach((consent) => {
                consent.value = consent.value.replace('_', ',');
            });

            checkoutData.setCustomerConsents(consents);
        }
        
    })
})
