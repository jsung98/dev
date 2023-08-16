<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2022 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 8/12/22
 * Time: 6:08 PM
 */

declare(strict_types=1);

namespace Eguana\NotifyMe\Block\Product;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * NotifyMe PopUp Template on PDP
 *
 * Class NotifyMe
 */
class NotifyMe extends \Magento\ProductAlert\Block\Product\View\Stock
{
    /**
     * @var CustomerSession
     */
    private CustomerSession $customerSession;
    /**
     * @var StockItemRepository
     */
    private StockItemRepository $_stockItemRepository;
    /**
     * @var Data
     */
    private Data $catalogHelper;
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\ProductAlert\Helper\Data $helper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\Helper\PostHelper $coreHelper,
        StockItemRepository   $stockItemRepository,
        Data                  $catalogHelper,
        CustomerSession       $customerSession,
        ScopeConfigInterface  $scopeConfig,
        StoreManagerInterface $storeManager,
        LoggerInterface       $logger,
        array $data = []
    ){
        $this->customerSession = $customerSession;
        $this->_stockItemRepository = $stockItemRepository;
        $this->catalogHelper = $catalogHelper;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        parent::__construct($context, $helper, $registry, $coreHelper, $data);
    }

    /**
     * Check Customer is Registered or Guest
     *
     * @return string
     */
    public function getCustomerStatus()
    {
        if ($this->customerSession->isLoggedIn()) {
            return '1';
        } else {
            return '0';
        }
    }

    /**
     * Get Registered User Email
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        if ($this->customerSession->isLoggedIn()) {
            return $this->customerSession->getCustomer()->getEmail();
        } else {
            return '';
        }
    }

    /**
     * Current Product
     *
     * @return Product|null
     */
    public function getCurrentProduct()
    {
        return $this->catalogHelper->getProduct();
    }

    /**
     * Get Product Stock Information
     *
     * @param int $productId
     * @return StockItemInterface
     */
    public function getStockItem(int $productId)
    {
        try {
            return $this->_stockItemRepository->get($productId);
        } catch (NoSuchEntityException $exception) {
            $this->logger->critical($exception->getMessage());
        }
    }

    /**
     * Url Path for ajax request
     *
     * @return string
     */
    public function createUrl()
    {
        return $this->getUrl("notify/check/user");
    }

    /**
     * Check is allowed for guest
     *
     * @return mixed
     */
    public function checkIsEnable()
    {
        $check_is_enable= '';
        try {
            if ($this->customerSession->isLoggedIn()) {
                $check_is_enable = $this->scopeConfig->getValue(
                    "catalog/productalert/allow_stock",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()->getStoreId()
                );
            } else {
                $check_is_enable = $this->scopeConfig->getValue(
                    "catalog/productalert/allow_stock_guest",
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $this->storeManager->getStore()->getStoreId()
                );
            }
        } catch (NoSuchEntityException $exception) {
            $this->logger->critical($exception->getMessage());
        }

        return $check_is_enable;
    }

    public function setTemplate($template)
    {
        $product = $this->catalogHelper->getProduct();
        $showNotification = $product->getData('show_notification');
        if (!$showNotification) {
            $template = '';
        }
        $this->_template = $template;
        return $this;
    }
}
