define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'underscore',
    'mage/validation',
    'mage/translate'
],function ($, alert, _) {

    return function (config) {
        var button = config.increase + ',' + config.decrease;

        $(document).on('click',  button, function (e) {
            var form = $('form#wishlist-view-form');
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
                        result = $(parsedResponse).find("#wishlist-view-form");

                    form.replaceWith(result);
                    result.trigger('contentUpdated');
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