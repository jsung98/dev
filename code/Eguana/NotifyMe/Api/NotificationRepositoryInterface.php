<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:05 PM
 */
namespace Eguana\NotifyMe\Api;

use Eguana\NotifyMe\Api\Data\NotificationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Declared inter
 * interface ContactusRepositoryInterface
 */
interface NotificationRepositoryInterface
{
    /**
     * Save Contactus.
     *
     * @param NotificationInterface $notification
     * @return NotificationInterface
     */
    public function save(NotificationInterface $contact): NotificationInterface;

    /**
     * Retrieve Contactus.
     *
     * @param int $notificationId
     * @return NotificationInterface
     */
   // public function getById(int $notificationId): NotificationInterface;

    /**
     * Retrieve list matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
     public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete notification.
     *
     * @param NotificationInterface $notification
     * @return bool true on success
     */
    //public function delete(NotificationInterface $notification): bool;

    /**
     * Delete notification by ID.
     *
     * @param $notificationId
     * @return bool true on success
     */
   // public function deleteById($notificationId): bool;
}
