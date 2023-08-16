<?php
/**
 * Created by PhpStorm.
 * User: Hyuna
 * Date: 2019-11-18
 * Time: PM 2:12
 */
namespace Eguana\AjaxQtyUpdate\Block\Cart;

use Eguana\AjaxQtyUpdate\Helper\Data;

/**
 * Shopping cart abstract block
 */
class AbstractCart
{
    /**
     * @var Data
     */
    private $data;

    /**
     * AbstractCart constructor.
     * @param Data $data
     */
    public function __construct(
        Data $data
    ) {
        $this->data = $data;
    }

    /**
     * @param \Magento\Checkout\Block\Cart\AbstractCart $subject
     * @param $result
     * @return mixed
     */
    public function afterGetItemRenderer(\Magento\Checkout\Block\Cart\AbstractCart $subject, $result)
    {
        if (!$this->data->getAjaxCartQtyStatus()) {
            return $result;
        }

        $result->setTemplate('Eguana_AjaxQtyUpdate::cart/item/default.phtml');
        return $result;
    }
}
