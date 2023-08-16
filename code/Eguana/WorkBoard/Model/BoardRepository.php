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

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Api\Data\BoardInterfaceFactory;
use Eguana\WorkBoard\Api\Data\BoardSearchResultsInterface;
use Eguana\WorkBoard\Api\Data\BoardSearchResultsInterfaceFactory;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Eguana\WorkBoard\Model\ResourceModel\Board as ResourceBoard;
use Eguana\WorkBoard\Model\ResourceModel\Board\Collection;
use Eguana\WorkBoard\Model\ResourceModel\Board\CollectionFactory as BoardCollectionFactory;
use Exception;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Model\AbstractModel;

/**
 * Class BoardRepository to perform CRUD operations
 */
class BoardRepository implements BoardRepositoryInterface
{
    /**
     * @var array
     */
    private $instances = [];

    /**
     * @var ResourceBoard
     */
    private $resource;

    /**
     * @var BoardCollectionFactory
     */
    private $boardCollectionFactory;

    /**
     * @var BoardSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var BoardInterfaceFactory
     */
    private $boardInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * BoardRepository constructor.
     *
     * @param ResourceBoard $resource
     * @param BoardCollectionFactory $boardCollectionFactory
     * @param BoardSearchResultsInterfaceFactory $boardSearchResultsInterfaceFactory
     * @param BoardInterfaceFactory $boardInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ResourceBoard $resource,
        BoardCollectionFactory $boardCollectionFactory,
        BoardSearchResultsInterfaceFactory $boardSearchResultsInterfaceFactory,
        BoardInterfaceFactory $boardInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->boardCollectionFactory = $boardCollectionFactory;
        $this->searchResultsFactory = $boardSearchResultsInterfaceFactory;
        $this->boardInterfaceFactory = $boardInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Save
     *
     * @param BoardInterface $board
     * @return BoardInterface|AbstractModel
     * @throws CouldNotSaveException
     */
    public function save(BoardInterface $board)
    {
        try {
            /** @var BoardInterface|AbstractModel $board */
            $this->resource->save($board);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the data: %1',
                $exception->getMessage()
            ));
        }
        return $board;
    }

    /**
     * Get By I'd
     *
     * @param int $boardId
     * @return BoardInterface|AbstractModel
     * @throws NoSuchEntityException
     */
    public function getById($boardId)
    {
        /** @var BoardInterface|AbstractModel $data */
        $data = $this->boardInterfaceFactory->create();
        $this->resource->load($data, $boardId);
        if (!$data->getId()) {
            throw new NoSuchEntityException(__('Requested board doesn\'t exist'));
        }
        return $data;
    }

    /**
     * Get List
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return BoardSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->boardCollectionFactory->create();
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addSortOrdersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
        $collection->load();

        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * Add Sort order to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addSortOrdersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ((array) $searchCriteria->getSortOrders() as $sortOrder) {
            $direction = $sortOrder->getDirection() == SortOrder::SORT_ASC ? 'asc' : 'desc';
            $collection->addOrder($sortOrder->getField(), $direction);
        }
    }

    /**
     * Add Paging to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addPagingToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $collection->setPageSize($searchCriteria->getPageSize());
        $collection->setCurPage($searchCriteria->getCurrentPage());
    }

    /**
     * Add Filter to description
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param FilterGroup $filterGroup
     * @param Collection $collection
     * @return void
     */
    protected function addFilterGroupToCollection(
        FilterGroup $filterGroup,
        Collection $collection
    ) {
        $fields = [];
        $categoryFilter = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $conditionType = $filter->getConditionType() ?: 'eq';

            if ($filter->getField() == 'board_id') {
                $categoryFilter[$conditionType][] = $filter->getValue();
                continue;
            }
            $fields[] = ['attribute' => $filter->getField(), $conditionType => $filter->getValue()];
        }

        if ($categoryFilter) {
            $collection->addCategoriesFilter($categoryFilter);
        }

        if ($fields) {
            $collection->addFieldToFilter($fields);
        }
    }

    /**
     * Delete
     *
     * @param BoardInterface $board
     * @return true
     * @throws StateException
     */
    public function delete(BoardInterface $board)
    {
        /** @var BoardInterface|AbstractModel $board */
        $id = $board->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($board);
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * Delete By Id
     *
     * @param int $boardId
     * @return true
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById($boardId)
    {
        $board = $this->getById($boardId);
        return $this->delete($board);
    }

    /**
     * Search Result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return BoardSearchResultsInterface
     */
    private function buildSearchResult(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
