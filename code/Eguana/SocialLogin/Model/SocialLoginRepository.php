<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2020 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Abbas Ali Butt
 * Date: 12/01/2023
 * Time: 12:42 PM
 */
namespace Eguana\SocialLogin\Model;

use Eguana\SocialLogin\Api\Data\SocialLoginInterface;
use Eguana\SocialLogin\Api\SocialLoginRepositoryInterface;
use Eguana\SocialLogin\Model\ResourceModel\SocialLogin;
use Eguana\SocialLogin\Model\ResourceModel\SocialLogin\Collection;
use Eguana\SocialLogin\Model\ResourceModel\SocialLogin\CollectionFactory as SocialLoginCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Repository for social login user's
 *
 * Class SocialLoginRepository
 *
 */
class SocialLoginRepository implements SocialLoginRepositoryInterface
{

    /**
     * @var SocialLoginFactory
     */
    private $socialLoginFactory;

    /**
     * @var SocialLoginCollectionFactory
     */
    private $socialLoginCollectionFactory;

    /**
     * @var
     */
    private $searchResultFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var Collection
     */
    private $collection;

    /**
     * @param SocialLoginFactory $socialLoginFactory
     * @param SocialLogin $sociallogin
     * @param SocialLoginCollectionFactory $socialLoginCollectionFactory
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param Collection $collection
     */
    public function __construct(
        SocialLoginFactory $socialLoginFactory,
        SocialLogin $sociallogin,
        SocialLoginCollectionFactory $socialLoginCollectionFactory,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        Collection $collection
    ) {
        $this->socialLoginFactory = $socialLoginFactory;
        $this->sociallogin = $sociallogin;
        $this->socialLoginCollectionFactory = $socialLoginCollectionFactory;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->collection = $collection;
    }

    /**
     * Get social media user by id
     * @param int $id
     * @return SocialLoginInterface|mixed
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $socialLoginUser = $this->socialLoginFactory->create();
        $this->sociallogin->load($socialLoginUser, $id);
        if (!$socialLoginUser->getSocialloginId()) {
            throw new NoSuchEntityException(__('Unable to find Social Login User with ID "%1"', $id));
        }
        return $socialLoginUser;
    }

    /**
     * Save social media user
     * @param SocialLoginInterface $socialLoginUser
     * @return SocialLoginInterface
     */
    public function save(SocialLoginInterface $socialLoginUser)
    {
        try {
            $this->sociallogin->save($socialLoginUser);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $socialLoginUser;
    }

    /**
     * Delete social media user
     * @param SocialLoginInterface $socialLoginUser
     */
    public function delete(SocialLoginInterface $socialLoginUser)
    {
        try {
            $this->sociallogin->delete($socialLoginUser);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Get social media customer data
     * @param $socialId
     * @param $socialMediaType
     * @return |null
     */
    public function getSocialMediaCustomer($socialId, $socialMediaType)
    {
        $websiteId = null;
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $customer = $this->socialLoginCollectionFactory->create();
        $dataUser = $customer->addFieldToFilter('social_id', $socialId)
            ->addFieldToFilter('socialmedia', $socialMediaType)
            ->addFieldToFilter('website_id', $websiteId)
            ->getFirstItem();
        if ($dataUser && $dataUser->getId()) {
            return $dataUser->getCustomerId();
        } else {
            return null;
        }
    }

    /**
     * check if already customer exist for specific media tyep
     * @param $customerId
     * @param $socialMediaType
     * @return bool
     */
    public function isCustomerExist($customerId, $socialMediaType)
    {
        $websiteId = null;
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $customer = $this->socialLoginCollectionFactory->create();
        $dataUser = $customer->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('socialmedia', ['eq' => $socialMediaType])
            ->addFieldToFilter('website_id', $websiteId)
            ->addFieldToFilter('social_id', ['neq' => '*****'])
            ->getFirstItem();
        if ($dataUser && $dataUser->getId()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * delete all linked customers using customer id
     * @param int $customerId
     * @return bool
     */
    public function deleteByCustomerId($customerId)
    {
        $bool = true;
        try {
            $customer = $this->socialLoginCollectionFactory->create();
            $dataUser = $customer->addFieldToFilter('customer_id', $customerId);
            if ($dataUser && $dataUser->getItems()) {
                $items = $dataUser->getItems();
                foreach ($items as $item) {
                    $user = $this->getById($item['sociallogin_id']);
                    $user->setSocialId('*****');
                    try {
                        $this->sociallogin->save($user);
                    } catch (\Exception $e) {
                        $this->logger->error($e->getMessage());
                        $bool = false;
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $bool;
    }



    /**
     * Get already linked customer with social media account
     * @param $customerId
     * @param $socialMediaType
     * @return |null
     */
    public function getAlreadyLinkedCustomer($customerId, $socialMediaType, $socialId)
    {
        $websiteId = null;
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $customer = $this->socialLoginCollectionFactory->create();
        $dataUser = $customer->addFieldToFilter('customer_id', $customerId)
            ->addFieldToFilter('socialmedia', $socialMediaType)
            ->addFieldToFilter('social_id', ['neq' => $socialId])
            ->addFieldToFilter('website_id', $websiteId);
        if ($dataUser && $dataUser->getItems()) {
            return $dataUser->getItems();
        } else {
            return null;
        }
    }
    /**
     * returns social login user
     * @param $id
     * @return DataObject|null
     */
    public function getSocialLoginUser($id)
    {
        return $this->collection->addFieldToFilter('customer_id', ['eq' => $id])->getFirstItem() ?: null;
    }


}
