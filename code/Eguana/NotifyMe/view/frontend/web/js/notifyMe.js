define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'Magento_Ui/js/modal/confirm'
], function ($, modal, confirm) {
    "use strict";
    return function (config, element) {
        $('.notify-me-modal').modal({
            type: 'popup',
            responsive: false,
            modalClass: 'notify-modal',
            title: $.mage.__('To request notifications'),
            buttons: [{
                text: $.mage.__('Submit'),
                class: 'action primary',
                click: function () {
                    if ($('#notification-form').valid()) {
                        let product_id =  $('#product_id').val();
                        let customer_email = $('#customer_email').val();
                        let customer_mobile = $('#customer_mobile').val();
                        $.ajax({
                            type: 'POST',
                            url: config.url,
                            data: {
                                product_id: product_id,
                                customer_email: customer_email,
                                customer_mobile: customer_mobile
                            },
                            dataType: 'json',
                            success: function (response) {
                                let confirmClass = 'confirm error';

                                if (response.is_added === 1) {
                                    confirmClass = 'confirm success';
                                    $('.notify-me-modal').modal('closeModal');
                                }

                                confirm({
                                    modalClass: confirmClass,
                                    content: $.mage.__(response.message),
                                    buttons: [{
                                        text: $.mage.__('Ok'),
                                        class: 'action-primary',

                                        /**
                                         * Close modal on button click
                                         */
                                        click: function (event) {
                                            this.closeModal(event);
                                        }
                                    }]
                                });
                            }
                        });
                    }
                }
            }]
        });
        $(config.alert_class).click(function(event) {
            event.preventDefault();
            $('#product_id').val(config.product_id);
            $('.notify-me-modal').modal('openModal');
        });
    }
});


