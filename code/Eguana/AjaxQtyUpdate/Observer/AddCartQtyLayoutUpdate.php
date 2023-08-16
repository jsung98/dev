<?php
/**
 * Created by PhpStorm.
 * User: Hyuna
 * Date: 2019-11-18
 * Time: PM 2:12
 */
namespace Eguana\AjaxQtyUpdate\Observer;

use Eguana\AjaxQtyUpdate\Helper\Data;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config as PageConfig;

class AddCartQtyLayoutUpdate implements ObserverInterface
{
    /**
     * @var PageConfig
     */
    protected $pageConfig;
    /**
     * @var Data
     */
    private $data;

    /**
     * AddCartQtyLayoutUpdate constructor.
     * @param Data $data
     * @param PageConfig $pageConfig
     */
    public function __construct(
        Data $data,
        PageConfig $pageConfig
    ) {
        $this->pageConfig = $pageConfig;
        $this->data = $data;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->data->getAjaxCartQtyStatus()) {
            return;
        }

        $this->pageConfig->addBodyClass('ajax-qty-update');
    }
}
