define([
    "jquery",
    "Magento_Ui/js/modal/alert"
], function($, alert) {
    'use strict';

    return function() {

        $("#submit-btn").click(function(e) {
            e.preventDefault();
            if ($('#download-form').valid()) {
                $.ajax({
                    url: $('#download-form').attr('action'),
                    type: 'post',
                    data: $('#download-form').serialize(),
                    beforeSend: function () {
                        $('body').trigger('processStart');
                    },
                    success: function(res) {
                        if (res.filepath) {
                            var link = document.createElement('a');
                            link.href = res.filepath;
                            link.download = 'filename';
                            link.style.display = 'none';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                            alert({
                                modalClass: 'confirm success',
                                content : $.mage.__('The download is complete. Thank you for Download.'),
                                title : $.mage.__('')
                            });
                        } else {
                            alert({
                                content: $.mage.__('Invalid response received. Please try again.'),
                                title : $.mage.__('')
                            });
                        }
                    },
                    error: function() {
                        alert({
                            content: $.mage.__('Sorry, something went wrong. Please try again later.'),
                            title : $.mage.__('')
                        });
                    },
                    complete: function () {
                        $('body').trigger('processStop');
                    }
                });
            }
        });
    }
});