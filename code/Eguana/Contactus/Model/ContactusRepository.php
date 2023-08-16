<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:55 PM
 */

namespace Eguana\Contactus\Model;

use Eguana\Contactus\Api\Data\ContactusInterface;
use Eguana\Contactus\Api\Data\ContactusInterfaceFactory;
use Eguana\Contactus\Api\Data\ContactusSearchResultsInterface;
use Eguana\Contactus\Api\Data\ContactusSearchResultsInterfaceFactory;
use Eguana\Contactus\Api\ContactusRepositoryInterface;
use Eguana\Contactus\Model\ResourceModel\Contactus;
use Eguana\Contactus\Model\ResourceModel\Contactus\Collection;
use Eguana\Contactus\Model\ResourceModel\Contactus\CollectionFactory as ContactusCollectionFactory;
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
class ContactusRepository implements ContactusRepositoryInterface
{
    /**
     * @var array
     */
    protected $instances = [];

    protected Contactus $resource;

    protected DataObjectHelper $dataObjectHelper;

    private ContactusCollectionFactory $contactCollectionFactory;

    private ContactusInterfaceFactory $contactInterfaceFactory;

    private ContactusSearchResultsInterfaceFactory $searchResultsFactory;

    public function __construct(
        Contactus                                $resource,
        ContactusCollectionFactory             $contactCollectionFactory,
        ContactusSearchResultsInterfaceFactory $contactSearchResultsInterfaceFactory,
        ContactusInterfaceFactory              $contactInterfaceFactory,
        DataObjectHelper                       $dataObjectHelper
    ) {
        $this->resource = $resource;
        $this->contactCollectionFactory = $contactCollectionFactory;
        $this->searchResultsFactory = $contactSearchResultsInterfaceFactory;
        $this->contactInterfaceFactory = $contactInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * Save Contact data
     *
     * @param ContactusInterface $contact
     * @return ContactusInterface
     * @throws CouldNotSaveException
     */
    public function save(ContactusInterface $contact): ContactusInterface
    {
        try {
            /** @var ContactusInterface|AbstractModel $contact */
            $this->resource->save($contact);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the data: %1',
                $exception->getMessage()
            ));
        }
        return $contact;
    }

    /**
     * delete user by id
     *
     * @param $contactId
     * @return ContactusInterface
     * @throws NoSuchEntityException
     */
    public function getById($contactId): ContactusInterface
    {
        /** @var ContactusInterface|AbstractModel $data */
        $data = $this->contactInterfaceFactory->create();
        $this->resource->load($data, $contactId);
        if (!$data->getId()) {
            throw new NoSuchEntityException(__('Requested user doesn\'t exist'));
        }
        return $data;
    }

    /**
     * get Contactlist by SearchCriteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->contactCollectionFactory->create();
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
     * delete contact by its model object
     *
     * @param ContactusInterface $contact
     * @return bool
     * @throws StateException
     */
    public function delete(ContactusInterface $contact): bool
    {
        /** @var ContactusInterface|AbstractModel $contact */
        $id = $contact->getId();
        try {
            unset($this->instances[$id]);
            $this->resource->delete($contact);
        } catch (Exception $e) {
            throw new StateException(
                __('Unable to remove data %1', $id)
            );
        }
        unset($this->instances[$id]);
        return true;
    }

    /**
     * delete contact by its id
     *
     * @param $contactId
     * @return bool
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById($contactId): bool
    {
        $contact = $this->getById($contactId);
        return $this->delete($contact);
    }

    /**
     * use for Searchbuilder result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return ContactusSearchResultsInterface
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
