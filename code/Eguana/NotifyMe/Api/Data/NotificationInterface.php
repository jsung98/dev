<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 11:51 AM
 */

namespace Eguana\NotifyMe\Api\Data;

/**
 * Interface class having getter\setter
 * interface ContactInterface
 */
interface NotificationInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ALERT_SELLING_ID = 'alert_selling_id';
    const CUSTOMER_EMAIL = 'customer_email';
    const CUSTOMER_MOBILE = 'customer_mobile';
    const PRODUCT_ID = 'product_id';
    const WEBSITE_ID = 'website_id';
    const STORE_ID = 'store_id';
    const ADD_DATE = 'add_date';
    const SEND_DATE = 'send_date';
    const SEND_COUNT = 'send_count';
    const STATUS = 'status';

    /**
     * Get alert selling id
     *
     * @return int
     */
    public function getAlertSellingId(): int;

    /**
     * Set Alert Selling Id
     *
     * @param int $alertSellingId
     * @return NotificationInterface
     */
    public function setAlertSellingId(int $alertSellingId): NotificationInterface;

    /**
     * Get customer email to the related notification
     *
     * @return string
     */
    public function getCustomerEmail(): string;

    /**
     * Set customer email to the related notification
     *
     * @param string $customerEmail
     * @return NotificationInterface
     */
    public function setCustomerEmail(string $customerEmail): NotificationInterface;

    /**
     * Get customer mobile to the related notification
     *
     * @return string
     */
    public function getCustomerMobile(): string;

    /**
     * Set customer mobile to the related notification
     *
     * @param string $customerMobile
     * @return NotificationInterface
     */
    public function setCustomerMobile(string $customerMobile): NotificationInterface;

    /**
     * Get product id for the related notification.
     *
     * @return int
     */
    public function getProductId():int;

    /**
     * Set product id for the related notification.
     *
     * @param int $productId
     * @return NotificationInterface
     */
    public function setProductId(int $productId): NotificationInterface;

    /**
     * Get Website id for the related notification
     *
     * @return int
     */
    public function getWebsiteId(): int;

    /**
     * Set Website id for the related notification
     *
     * @param int $websiteId
     * @return NotificationInterface
     */
    public function setWebsiteId(int $websiteId): NotificationInterface;
    /**
     * Get  Store id of the notification
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set store id of the related notification
     *
     * @param int $storeId
     * @return NotificationInterface
     */
    public function setStoreId(int $storeId): NotificationInterface;

    /**
     * Get date when notification was added
     *
     * @return string
     */
    public function getAddDate(): string;

    /**
     * Set Add date of notification
     *
     * @param string $addDate
     * @return NotificationInterface
     */
    public function setAddDate(string $addDate): NotificationInterface;

    /**
     * Get  Send Date
     *
     * @return string|null
     */
    public function getSendDate(): ?string;

    /**
     * Set Send Date of notification
     *
     * @param string $sendData
     * @return NotificationInterface
     */
    public function setSendDate(string $sendData): NotificationInterface;

    /**
     * get send count of notification
     *
     * @return int
     */
    public function getSendCount(): int;

    /**
     * Set send count of notification
     *
     * @param int $sendCount
     * @return NotificationInterface
     */
    public function setSendCount(int $sendCount): NotificationInterface;

    /**
     * Get  Notification status
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     * Set Status of notification
     *
     * @param int $status
     * @return NotificationInterface
     */
    public function setStatus(int $status): NotificationInterface;
}
