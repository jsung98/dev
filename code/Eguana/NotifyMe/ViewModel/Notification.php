<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Abbas
 * Date: 28/03/23
 * Time: 5:34 PM
 */
namespace Eguana\NotifyMe\ViewModel;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Notification implements ArgumentInterface
{
    private LoggerInterface $logger;
    private UrlInterface $url;
    private StoreManagerInterface $storeManager;
    /**
     * @param LoggerInterface $logger
     * @param UrlInterface $url
     * @param StoreManagerInterface $storeManager
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        LoggerInterface $logger,
        UrlInterface $url,
        StoreManagerInterface $storeManager
    ) {
        $this->logger = $logger;
        $this->url = $url;
        $this->storeManager = $storeManager;
    }

    /**
     * Get url of Notification submit url
     *
     *
     * @return string
     */
    public function getNotificationSubmitUrl() : string
    {
        $Url = '';
        try {
            $Url = $this->url->getUrl("notify/add/guest");
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $Url;
    }

}
