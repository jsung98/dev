<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:06 PM
 */
namespace Eguana\Download\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface DownloadSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get $download list.
     *
     * @return DownloadInterface[]
     */
    public function getItems();

    /**
     * Set $download list.
     *
     * @param DownloadInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
