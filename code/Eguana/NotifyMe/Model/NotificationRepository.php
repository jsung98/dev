<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 1/12/21
 * Time: 12:55 PM
 */

namespace Eguana\NotifyMe\Model;

use Eguana\NotifyMe\Api\NotificationRepositoryInterface;
use Eguana\NotifyMe\Api\Data\NotificationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Eguana\NotifyMe\Model\ResourceModel\Notification;
use Eguana\NotifyMe\Api\Data\NotificationSearchResultsInterface;
use Eguana\NotifyMe\Api\Data\NotificationSearchResultsInterfaceFactory;
use Eguana\NotifyMe\Model\ResourceModel\Notification\Collection;
use Eguana\NotifyMe\Model\ResourceModel\Notification\CollectionFactory as NotificationCollectionFactory;


/**
 * Class NotificationRepository to perform CRUD operations
 */
class NotificationRepository implements NotificationRepositoryInterface
{

    private Notification $resource;
    private NotificationSearchResultsInterfaceFactory $searchResultsFactory;
    private NotificationCollectionFactory $notificationCollectionFactory;

    public function __construct(
        Notification  $resource,
        NotificationSearchResultsInterfaceFactory $notificationSearchResultsInterfaceFactory,
        NotificationCollectionFactory $notificationCollectionFactory
    ) {
        $this->resource = $resource;
        $this->searchResultsFactory = $notificationSearchResultsInterfaceFactory;
        $this->notificationCollectionFactory = $notificationCollectionFactory;
    }

    /**
     * @inheirtDoc
     */
    public function save(NotificationInterface $notification): NotificationInterface
    {
        try {
            /** @var NotificationInterface|AbstractModel $notification */
            $this->resource->save($notification);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the data: %1',
                $exception->getMessage()
            ));
        }
        return $notification;
    }

    /**
     * @inheirtDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        $collection = $this->notificationCollectionFactory->create();
        $this->addFiltersToCollection($searchCriteria, $collection);
        $collection->load();
        return $this->buildSearchResult($searchCriteria, $collection);
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
     * use for Searchbuilder result
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param Collection $collection
     * @return NotificationSearchResultsInterface
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
