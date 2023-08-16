<?php
/**
 * Created by PhpStorm.
 * User: Hyuna
 * Date: 2019-11-18
 * Time: PM 2:12
 */

namespace Eguana\AjaxQtyUpdate\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_PATH_AJAX_QTY_CART = 'ajax_qty_update_status/general/ajax_shopping_cart_enabled';

    /**
     * Get Ajax Qty Update Status in Shopping Cart
     * @return bool
     */
    public function getAjaxCartQtyStatus()
    {
        return (bool) $this->scopeConfig->getValue(self::XML_PATH_AJAX_QTY_CART, ScopeInterface::SCOPE_STORE);
    }
}
