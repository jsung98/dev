<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eguana\CustomSales\Block\Order;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class Recent extends \Magento\Sales\Block\Order\Recent
{
    /**
     * Limit of orders
     */
    const ORDER_LIMIT = 5;

    /**
     * @var CollectionFactoryInterface
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param CollectionFactoryInterface $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        CollectionFactoryInterface $orderCollectionFactory,
        Session $customerSession,
        Config $orderConfig,
        array $data = [],
        StoreManagerInterface $storeManager = null
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->_isScopePrivate = true;
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data, $storeManager);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->getRecentOrders();
    }

    /**
     * Get recently placed orders. By default they will be limited by 5.
     */
    private function getRecentOrders()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $orders = $this->_orderCollectionFactory->create($customerId)->addAttributeToSelect(
            '*'
        )->addAttributeToFilter(
            'customer_id',
            $customerId
        )->addAttributeToFilter(
            'store_id',
            $this->storeManager->getStore()->getId()
        )->addAttributeToFilter(
            'status',
            ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
        )->addAttributeToSort(
            'created_at',
            'desc'
        )->setPageSize(
            self::ORDER_LIMIT
        )->load();
        $this->setOrders($orders);
    }

    /**
     * @inheritDoc
     */
    protected function _toHtml()
    {
        if (!$this->getTemplate()) {
            return '';
        }
        return $this->fetchView($this->getTemplateFile());
    }
}
