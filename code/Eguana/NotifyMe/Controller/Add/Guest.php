<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2022 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 21/12/22
 * Time: 10:14 AM
 */

declare(strict_types=1);

namespace Eguana\NotifyMe\Controller\Add;

use Eguana\NotifyMe\Model\NotificationFactory;
use Eguana\NotifyMe\Model\NotificationRepository;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;
use Eguana\NotifyMe\Model\Notification;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Save Product availability Alerts for Guest
 *
 * Class Guest
 */
class Guest implements HttpPostActionInterface
{
    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /** @var NotificationRepository $notificationRepository */
    private NotificationRepository $notificationRepository;
    private NotificationFactory $notificationFactory;
    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;
    /**
     * @var JsonFactory
     */
    private JsonFactory $resultJsonFactory;
    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    public function __construct(
        JsonFactory  $resultJsonFactory,
        NotificationRepository $notificationRepository,
        NotificationFactory  $notificationFactory,
        RequestInterface $request,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->notificationFactory = $notificationFactory;
        $this->request = $request;
        $this->notificationRepository = $notificationRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }


    /**
     * Saving Stock Alert Data For Guest
     *
     * @return ResponseInterface|Json|Redirect|ResultInterface
     * @throws NoSuchEntityException
     */

    public function execute()
    {
        /** @var Json $result */
        $result = $this->resultJsonFactory->create();
        $alertMessage = '';
        $isAdded = 0;
        $customerEmail = $this->request->getParam('customer_email');
        $customerMobile = $this->request->getParam('customer_mobile');
        $productId = (int)$this->request->getParam('product_id');
        $currentWebsiteId = (int)$this->storeManager->getStore()->getWebsiteId();
        $currentStoreId = (int)$this->storeManager->getStore()->getStoreId();

        try {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('customer_email', $customerEmail , 'eq')
                ->addFilter('customer_mobile', $customerMobile, 'eq')
                ->addFilter('product_id', $productId, 'eq')
                ->addFilter('website_id', $currentWebsiteId, 'eq')
                ->addFilter('store_id', $currentStoreId, 'eq')
                ->create();
            $notifications = $this->notificationRepository->getList($searchCriteria);
            $sameNotifications = $notifications->getItems();
            if(count($sameNotifications) == 0) {
                /** @var Notification $notification */
                $notification = $this->notificationFactory->create();
                $notification->setCustomerEmail($customerEmail);
                $notification->setCustomerMobile($customerMobile);
                $notification->setWebsiteId($currentWebsiteId);
                $notification->setStoreId($currentStoreId);
                $notification->setProductId($productId);
                $this->notificationRepository->save($notification);
                $alertMessage = __('Alert subscription has been saved.');
                $isAdded = 1;
            } else {
                $alertMessage = __('Already have a notification');
            }
        }  catch (\Exception $e) {
            $alertMessage = $e->getMessage();
        }
        $data = [
            'is_added' => $isAdded,
            'message' => $alertMessage
        ];
        return $result->setData($data);
    }
}
