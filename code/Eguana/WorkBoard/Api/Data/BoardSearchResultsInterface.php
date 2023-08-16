<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2023 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 12/1/23
 * Time: 4:27 PM
 */

declare(strict_types=1);

namespace Eguana\WorkBoard\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for Board search results.
 * Interface BoardSearchResultsInterface
 */
interface BoardSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Board list.
     *
     * @return BoardInterface[]
     */
    public function getItems();

    /**
     * Set Board list.
     *
     * @param BoardInterface[] $items
     * @return BoardInterface
     */
    public function setItems(array $items);
}
