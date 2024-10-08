/**
 * @author Endora
 * @copyright Copyright (c) Endora (https://endora.software)
 * @package ExpertSender_Ecdp
*/

define([
    'Magento_Customer/js/customer-data'
], function (storage) {
    let cacheKey = 'checkout-data',

        saveData = function (data) {
            storage.set(cacheKey, data);
        },

        getData = function () {
            return storage.get(cacheKey);
        };
    
    return function (checkoutData) {
        checkoutData.setCustomerConsents = function (data) {
            let obj = getData();
            obj.customerConsents = data;
            saveData(obj);
        }

        checkoutData.getCustomerConsents = function () {
            return getData().customerConsents;
        }

        return checkoutData;
    }
});
