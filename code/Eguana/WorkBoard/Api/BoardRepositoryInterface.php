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

namespace Eguana\WorkBoard\Api;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Declared CRUD
 *
 * interface BoardRepositoryInterface
 */
interface BoardRepositoryInterface
{
    /**
     * Save Board.
     *
     * @param BoardInterface $board
     * @return BoardInterface
     */
    public function save(BoardInterface $board);

    /**
     * Retrieve Board.
     *
     * @param int $boardId
     * @return BoardInterface
     */
    public function getById($boardId);

    /**
     * Retrieve list matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Board.
     *
     * @param BoardInterface $board
     * @return bool true on success
     */
    public function delete(BoardInterface $board);

    /**
     * Delete Board by ID.
     *
     * @param int $boardId
     * @return bool true on success
     */
    public function deleteById($boardId);
}
