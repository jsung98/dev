<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: bilalyounas
 * Date: 17/3/21
 * Time: 11:12 AM
 */

namespace Eguana\NotifyMe\Ui\Component;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Api\SearchCriteriaBuilder as CoreSearchCriteriaBuilder;
use Eguana\NotifyMe\Model\NotificationRepository;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as CoreDataProvider;
use Magento\Framework\View\Element\UiComponent\DataProvider\Reporting;

class DataProvider extends CoreDataProvider
{
    private AuthorizationInterface $authorization;
    private NotificationRepository $notificationStoreRepository;
    private CoreSearchCriteriaBuilder $coreSearchCriteriaBuilder;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param Reporting $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Reporting $reporting,
        NotificationRepository $notificationStoreRepository,
        CoreSearchCriteriaBuilder $coreSearchCriteriaBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        AuthorizationInterface $authorization,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
        $this->authorization = $authorization;
        $this->coreSearchCriteriaBuilder = $coreSearchCriteriaBuilder;
        $this->notificationStoreRepository = $notificationStoreRepository;
       // $this->meta = array_replace_recursive($meta, $this->prepareMetadata());
    }

    public function getData()
    {
        $collection = parent::getData();
        foreach ($collection['items'] as $key => $notification) {
            $storeIds = $this->getNotificationStore($notification['alert_selling_id']);
            $collection['items'][$key]['store_id'] = $storeIds;
        }
        return $collection;
    }

    public function getNotificationStore($notificationId)
    {
        $searchCriteria = $this->coreSearchCriteriaBuilder
            ->addFilter('alert_selling_id', $notificationId, 'eq')->create();
        $stores = $this->notificationStoreRepository->getList($searchCriteria)->getItems();
        $storeIds = [];
        foreach ($stores as $store) {
            $storeIds[] = $store->getStoreId();
        }
        return $storeIds;
    }
}
