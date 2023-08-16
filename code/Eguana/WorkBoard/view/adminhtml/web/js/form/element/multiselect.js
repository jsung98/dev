/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2023 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 12/1/23
 * Time: 4:27 PM
 */
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/multiselect',
    'mage/url',
    'Magento_Ui/js/modal/modal'
], function ($, _, uiRegistry, multiselect, url) {
    'use strict';
    return multiselect.extend({
        hasChanged: function () {
            let requestUrl = window.BASE_URL;
            let splitUrl = requestUrl.split('board');
            let elem = this.inputName;
            url.setBaseUrl(BASE_URL);
            let apiUrl =  splitUrl[0]+'board/manage/ajaxcall';
            let category = "select[name='category']";
            if(elem == 'store_id') {
                $.ajax({
                    url: apiUrl,
                    type: "POST",
                    showLoader: true,
                    data: {store_id:  this.value()},
                }).done(function (result) {
                    $(category).html('');
                    $(category).html(result.category);
                });
            }
            return this._super();
        }
    });
});
