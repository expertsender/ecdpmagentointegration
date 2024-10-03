var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/place-order': {
                'Endora_ExpertSenderCdp/js/mixin/place-order-mixin': true
            },
            'Magento_Checkout/js/action/set-payment-information': {
                'Endora_ExpertSenderCdp/js/mixin/set-payment-information-mixin': true
            },
            'Magento_Checkout/js/checkout-data': {
                'Endora_ExpertSenderCdp/js/mixin/consent-checkout-data': true
            }
        }
    }
};
