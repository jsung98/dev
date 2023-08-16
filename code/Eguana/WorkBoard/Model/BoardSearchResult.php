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

namespace Eguana\WorkBoard\Model;

use Eguana\WorkBoard\Api\Data\BoardSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * BoardSearchResult
 *
 * Class BoardSearchResult
 *  Eguana\WorkBoard\Model
 */
class BoardSearchResult extends SearchResults implements BoardSearchResultsInterface
{

}
