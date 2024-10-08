var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'ExpertSender_Ecdp/js/mixin/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'ExpertSender_Ecdp/js/mixin/set-payment-information-mixin': true
            },
            'Magento_Checkout/js/checkout-data': {
                'ExpertSender_Ecdp/js/mixin/consent-checkout-data': true
            }
        }
    }
};
