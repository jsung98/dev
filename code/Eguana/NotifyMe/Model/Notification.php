<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 30/11/21
 * Time: 3:24 PM
 */
namespace Eguana\NotifyMe\Model;

use Magento\Framework\Model\AbstractModel;
use Eguana\NotifyMe\Model\ResourceModel\Notification as ResourceModel;
use Eguana\NotifyMe\Api\Data\NotificationInterface;

class Notification extends AbstractModel implements NotificationInterface
{
    /**
     * @var string
     */
    protected $_cacheTag = 'eguana_notification';

    /**
     * @var string
     */
    protected $_eventPrefix = 'eguana_notification';

    /**
     * Initialize resource model
     *
     * @return void
     */

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheirtDoc
     */
    public function getAlertSellingId():int
    {
        return $this->getData(NotificationInterface::ALERT_SELLING_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setAlertSellingId(int $alertSellingId): NotificationInterface
    {
        return $this->setData(NotificationInterface::ALERT_SELLING_ID, $alertSellingId);
    }

    /**
     * @inheirtDoc
     */
    public function getCustomerEmail(): string
    {
        return $this->getData(NotificationInterface::CUSTOMER_EMAIL);
    }

    /**
     * @inheirtDoc
     */
    public function setCustomerEmail(string $customerEmail): NotificationInterface
    {
        return $this->setData(NotificationInterface::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @inheirtDoc
     */
    public function getCustomerMobile(): string
    {
        return $this->getData(NotificationInterface::CUSTOMER_MOBILE);
    }

    /**
     * @inheirtDoc
     */
    public function setCustomerMobile(string $customerMobile): NotificationInterface
    {
        return $this->setData(NotificationInterface::CUSTOMER_MOBILE, $customerMobile);
    }

    /**
     * @inheirtDoc
     */
    public function getProductId():int
    {
        return $this->getData(NotificationInterface::PRODUCT_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setProductId(int $productId): NotificationInterface
    {
        return $this->setData(NotificationInterface::PRODUCT_ID, $productId);
    }

    /**
     * @inheirtDoc
     */
    public function getWebsiteId(): int
    {
        return $this->getData(NotificationInterface::WEBSITE_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setWebsiteId(int $websiteId): NotificationInterface
    {
        return $this->setData(NotificationInterface::WEBSITE_ID, $websiteId);
    }

    /**
     * @inheirtDoc
     */
    public function getStoreId(): int
    {
        return $this->getData(NotificationInterface::STORE_ID);
    }

    /**
     * @inheirtDoc
     */
    public function setStoreId(int $storeId): NotificationInterface
    {
        return $this->setData(NotificationInterface::STORE_ID, $storeId);
    }

    /**
     * @inheirtDoc
     */
    public function getAddDate(): string
    {
        return $this->getData(NotificationInterface::ADD_DATE);
    }

    /**
     * @inheirtDoc
     */
    public function setAddDate(string $addDate): NotificationInterface
    {
        return $this->setData(NotificationInterface::ADD_DATE, $addDate);
    }

    /**
     * @inheirtDoc
     */
    public function getSendDate(): ?string
    {
        return $this->getData(NotificationInterface::SEND_DATE);
    }

    /**
     * @inheirtDoc
     */
    public function setSendDate(string $sendData): NotificationInterface
    {
        return $this->setData(NotificationInterface::SEND_DATE, $sendData);
    }

    /**
     * @inheirtDoc
     */
    public function getSendCount(): int
    {
        return $this->getData(NotificationInterface::SEND_COUNT);
    }

    /**
     * @inheirtDoc
     */
    public function setSendCount(int $sendCount): NotificationInterface
    {
        return $this->setData(NotificationInterface::SEND_COUNT, $sendCount);
    }

    /**
     * @inheirtDoc
     */
    public function getStatus(): int
    {
        return $this->getData(NotificationInterface::STATUS);
    }

    /**
     * @inheirtDoc
     */
    public function setStatus(int $status): NotificationInterface
    {
        return $this->setData(NotificationInterface::STATUS, $status);
    }

}
