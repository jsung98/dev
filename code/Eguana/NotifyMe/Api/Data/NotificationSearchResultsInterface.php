<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:06 PM
 */
namespace Eguana\NotifyMe\Api\Data;

use Eguana\NotifyMe\Api\Data\NotificationInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface NotificationSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get notifications list.
     *
     * @return NotificationInterface[]
     */
    public function getItems();

    /**
     * Set $Contact list.
     *
     * @param NotificationInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
