<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:06 PM
 */
namespace Eguana\Contactus\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface ContactusSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get $Contact list.
     *
     * @return ContactusInterface[]
     */
    public function getItems();

    /**
     * Set $Contact list.
     *
     * @param ContactusInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
