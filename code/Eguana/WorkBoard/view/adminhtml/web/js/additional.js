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
    'mage/url',
    'domReady!'
], function ($, url) {
    'use strict';
    function main() {
        let storeViewByName = "select[name='store_id']";
        $(document).on('change', storeViewByName, function (){
            let storeIds = $(storeViewByName).val();
            let requestUrl = window.BASE_URL;
            let splitUrl = requestUrl.split('board');
            let category = "select[name='category']";
            $.ajax({
                url: splitUrl[0]+'board/manage/ajaxcall',
                type: "POST",
                showLoader: true,
                data: {store_id:  storeIds, form_key: window.FORM_KEY},
            }).done(function (result) {
                $(category).html('');
                $(category).html(result.category);
            });
        });
    };
    return main;
});
