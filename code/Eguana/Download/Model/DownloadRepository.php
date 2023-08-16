<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:55 PM
 */

namespace Eguana\Download\Model;

use Eguana\Download\Api\Data\DownloadInterface;
use Eguana\Download\Api\Data\DownloadInterfaceFactory;
use Eguana\Download\Api\Data\DownloadSearchResultsInterface;
use Eguana\Download\Api\Data\DownloadSearchResultsInterfaceFactory;
use Eguana\Download\Api\DownloadRepositoryInterface;
use Eguana\Download\Model\ResourceModel\Download;
use Eguana\Download\Model\ResourceModel\Download\Collection;
use Eguana\Download\Model\ResourceModel\Download\CollectionFactory as DownloadCollectionFactory;
use Exception;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Model\AbstractModel;

/**
 * Class ContactusRepository to perform CRUD operations
 */
class DownloadRepository implements DownloadRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];

    protected Download $resource;

    protected DataObjectHelper $dataObjectHelper;

    private DownloadCollectionFactory $downloadCollectionFactory;

    private DownloadInterfaceFactory $downloadInterfaceFactory;

    private DownloadSearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        Download                               $resource,
        DownloadCollectionFactory              $downloadCollectionFactory,
        DownloadSearchResultsInterfaceFactory  $downloadSearchResultsInterfaceFactory,
        DownloadInterfaceFactory               $downloadInterfaceFactory,
        DataObjectHelper                       $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->downloadCollectionFactory = $downloadCollectionFactory;
        $this->searchResultsFactory = $downloadSearchResultsInterfaceFactory;
        $this->downloadInterfaceFactory = $downloadInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Save Download data
     *
     * @param DownloadInterface $download
     * @return DownloadInterface
     * @throws CouldNotSaveException
     */
    public function save(DownloadInterface $download): DownloadInterface
    {
        try {
            /** @var DownloadInterface|AbstractModel $download */
            $this->resource->save($download);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the data: %1',
                $exception->getMessage()
            ));
        }
        return $download;
    }

    public function create(): DownloadInterface
    {
        return $this->downloadInterfaceFactory->create();
    }

    /**
     * delete user by id
     *
     * @param $downloadId
     * @return DownloadInterface
     * @throws NoSuchEntityException
     */
    public function getById($downloadId): DownloadInterface
    {
        /** @var DownloadInterface|AbstractModel $data */
        $data = $this->downloadInterfaceFactory->create();
        $this->resource->load($data, $downloadId);
        if (!$data->getId()) {
            throw new NoSuchEntityException(__('Requested user doesn\'t exist'));
        }
        return $data;
    }

    /**
     * get Downloadlist by SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->downloadCollectionFactory->create();
        $this->addFiltersToCollection($searchCriteria, $collection);
        $this->addPagingToCollection($searchCriteria, $collection);
        $collection->load();
        return $this->buildSearchResult($searchCriteria, $collection);
    }

    /**
     * add pagination in collection
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
     * add filter to collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     */
    private function addFiltersToCollection(SearchCriteriaInterface $searchCriteria, Collection $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterFieldSet) {
            $fields = $conditions = [];
            foreach ($filterFieldSet->getFilters() as $filter) {
                $fields[] = $filter->getField();
                $conditions[] = [$filter->getConditionType() => $filter->getValue()];
            }
            $collection->addFieldToFilter($fields, $conditions);
        }
    }

    /**
     * delete download by its model object
     *
     * @param DownloadInterface $download
     * @return bool
     * @throws StateException
     */
    public function delete(DownloadInterface $download): bool
    {
        /** @var DownloadInterface|AbstractModel $download */
        $id = $download->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($download);
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * delete download by its id
     *
     * @param $downloadId
     * @return bool
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById($downloadId): bool
    {
        $download = $this->getById($downloadId);
        return $this->delete($download);
    }

    /**
     * use for Searchbuilder result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return DownloadSearchResultsInterface
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
