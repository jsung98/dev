define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/shipping-rate-processor/new-address',
    'Magento_Checkout/js/model/shipping-rate-processor/customer-address',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/action/get-totals',
    'underscore',
    'mage/validation',
    'mage/translate'
], function ($, alert, quote, defaultProcessor, customerAddressProcessor, rateRegistry, getTotals, _) {
    'use strict';

    return function (config) {
        var button = config.increase + ',' + config.decrease;

        $(document).on('click',  button, function (e) {

            var form = $('form#form-validate');
            if (!(form.validation() && form.validation('isValid'))) {
                return;
            }

            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                type: 'post',
                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                success: function (res) {
                    var parsedResponse = $.parseHTML(res),
                        result = $(parsedResponse).find("#form-validate");

                    form.replaceWith(result);

                    /*The totals summary block reloading */
                    var deferred = $.Deferred();
                    getTotals([], deferred);
                },

                error: function () {
                    alert({
                        content: $.mage.__('Sorry, something went wrong. Please try again later.')
                    });
                },

                /**
                 * Complete.
                 */
                complete: function () {
                    $('body').trigger('processStop');
                }
            });
        });
    }
});