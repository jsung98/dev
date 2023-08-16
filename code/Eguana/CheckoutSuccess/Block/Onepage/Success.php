<?php
namespace Eguana\CheckoutSuccess\Block\Onepage;

use Magento\Catalog\Helper\Image;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order;
use Magento\Checkout\Model\Session;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Pricing\Helper\Data;


class Success extends Template
{
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var Image
     */
    protected $imageHelper;
    /**
     * @var Data
     */
    protected $priceHelper;
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @param Context $context
     * @param Session $checkoutSession
     * @param Order $order
     * @param Data $priceHelper
     * @param PriceCurrencyInterface $priceCurrency
     * @param Image $imageHelper
     * @param array $data
     */

    public function __construct(
        Template\Context           $context,
        Session                    $checkoutSession,
        Order                      $order,
        Data                       $priceHelper,
        PriceCurrencyInterface     $priceCurrency,
        Image                      $imageHelper,
        array                      $data = []
    ) {
        $this->order = $order;
        $this->priceHelper = $priceHelper;
        $this->priceCurrency = $priceCurrency;
        $this->imageHelper = $imageHelper;
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * get recent order
     * @return Order|null
     */
    public function getOrder()
    {
        if (!$this->order->getId()) {
            $this->order = $this->checkoutSession->getLastRealOrder();
        }
        return $this->order->getId() ? $this->order : null;
    }

    /**
     * get order item
     * @return array|null
     */
    public function getOrderItems()
    {
        $order = $this->getOrder();
        if ($order) {
            return $order->getAllVisibleItems();
        }
        return null;
    }

    /**
     * get price and formatting
     * @param $amount
     * @return string
     */
    public function getFormattedPrice($amount) {
        return $this->priceCurrency->convertAndFormat($amount);
    }

    /**
     * get imagehelper
     * @return Image
     */
    public function getImageHelper()
    {
        return $this->imageHelper;
    }

}
