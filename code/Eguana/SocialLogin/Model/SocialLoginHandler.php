<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2020 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: silentarmy
 * Date: 13/6/20
 * Time: 1:03 AM
 */
namespace Eguana\SocialLogin\Model;

use Eguana\SocialLogin\Model\ResourceModel\SocialLogin\CollectionFactory as SocialMediaCollectionFactory;
use Eguana\SocialLogin\Model\SocialLoginFactory as SocialMediaModelFactory;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface as ManagerInterfaceAlias;
use Magento\Framework\Session\SessionManagerInterface as SessionManagerInterfaceAlias;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Handler class
 *
 * Class SocialLoginHandler
 */
class SocialLoginHandler
{
    /**
     * @var CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var ManagerInterfaceAlias
     */
    protected $messageManager;
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;
    /**
     * @var Customer
     */
    protected $customerModel;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepositoryInterface;
    /**
     * @var SocialMediaCollectionFactory
     */
    protected $socialMediaCustomerCollectionFactory;
    /**
     * @var SocialMediaModelFactory
     */
    protected $socialMediaCustomerModelFactory;
    /**
     * @var SocialLoginRepository
     */
    private $socialLoginRepository;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * SocialLoginHandler constructor.
     * @param CustomerFactory $customerFactory
     * @param ManagerInterfaceAlias $messageManager
     * @param Session $customerSession
     * @param Customer $customerModel
     * @param LoggerInterface $logger
     * @param CustomerRepository $customerRepository
     * @param SocialMediaCollectionFactory $socialMediaCustomerCollectionFactory
     * @param SocialLoginFactory $socialMediaCustomerModelFactory
     * @param SessionManagerInterfaceAlias $coreSession
     * @param PageFactory $resultPageFactory
     * @param SocialLoginRepository $socialLoginRepository
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        CustomerFactory $customerFactory,
        ManagerInterfaceAlias $messageManager,
        Session $customerSession,
        Customer $customerModel,
        LoggerInterface $logger,
        CustomerRepository $customerRepository,
        SocialMediaCollectionFactory $socialMediaCustomerCollectionFactory,
        SocialMediaModelFactory $socialMediaCustomerModelFactory,
        SessionManagerInterfaceAlias $coreSession,
        PageFactory $resultPageFactory,
        SocialLoginRepository $socialLoginRepository,
        StoreManagerInterface $storeManager
    ) {
        $this->customerRepository                = $customerRepository;
        $this->customerFactory                   = $customerFactory;
        $this->messageManager                    = $messageManager;
        $this->session                           = $customerSession;
        $this->customerModel                     = $customerModel;
        $this->logger                            = $logger;
        $this->socialMediaCustomerCollectionFactory  = $socialMediaCustomerCollectionFactory;
        $this->socialMediaCustomerModelFactory   = $socialMediaCustomerModelFactory;
        $this->coreSession                       = $coreSession;
        $this->resultPageFactory                 = $resultPageFactory;
        $this->socialLoginRepository             = $socialLoginRepository;
        $this->storeManager                      = $storeManager;
    }

    /**
     * Get session instance
     * @return SessionManagerInterfaceAlias
     */
    public function getCoreSession()
    {
        return $this->coreSession;
    }

    /**
     * Set data in session
     * @param $customerData
     */
    private function setDataInSession($customerData)
    {
        $this->getCoreSession()->start();
        $this->getCoreSession()->unsSocialUserData();
        $this->getCoreSession()->setData('social_user_data', $customerData);
    }

    /**
     * Get customer data for session
     * @param $dataUser
     * @param $userId
     * @param $socialMediaType
     * @return array|null
     */
    private function getCustomerData($dataUser, $userId, $socialMediaType)
    {
        if($socialMediaType=='facebook') {
            if ($dataUser) {
                $customerData = [];
                $customerData['appid'] = $userId;
                if (array_key_exists('name', $dataUser)) {
                    $customerData['name'] = $dataUser['name'];
                }
                if (array_key_exists('picture', $dataUser)) {
                    $customerData['picture'] = $dataUser['picture'];
                }
                if (array_key_exists('email', $dataUser)) {
                    $customerData['email'] = $dataUser['email'];
                }
                $customerData['socialmedia_type'] = $socialMediaType;
                return $customerData;
            }
        }
        elseif ($socialMediaType=='kakao'){
            if ($dataUser) {
                $customerData = [];
                $customerData['appid'] = $userId;
                $customerData['name'] = '';
                if (array_key_exists('properties', $dataUser)) {
                    $res = $dataUser['properties'];
                    $customerData['name']=$res['nickname'];
                }
                if (array_key_exists('kakao_account', $dataUser)) {
                    $res = $dataUser['kakao_account'];
                    if($res['email_needs_agreement']== true){
                        $customerData['email']=null;
                    }
                    else{
                        $customerData['email']=$res['email'];
                    }
                }
                $customerData['socialmedia_type'] = $socialMediaType;
                return $customerData;
            }
        }
        return null;
    }

    /**
     * Save social media customer data
     * @param $socialId
     * @param $customerId
     * @param $username
     * @param $socialMediaType
     */
    public function setSocialMediaCustomer($socialId, $customerId, $username, $socialMediaType)
    {
        $websiteId = null;
        try {
            $websiteId = $this->storeManager->getStore()->getWebsiteId();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $socialMediaCustomer = $this->socialMediaCustomerModelFactory->create();
        $socialMediaCustomer->setData('social_id', $socialId);
        $socialMediaCustomer->setData('username', $username);
        $socialMediaCustomer->setData('socialmedia', $socialMediaType);
        $socialMediaCustomer->setData('customer_id', $customerId);
        $socialMediaCustomer->setData('website_id', $websiteId);
        try {
            $this->socialLoginRepository->save($socialMediaCustomer);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Redirect customer to other page
     * If customer exists then login and close popup else close pop and redirect to social login page
     * @param $customerId
     * @param $dataUser
     * @param $userid
     * @param $socialMediaType
     */
    public function redirectCustomer($dataUser, $userid, $socialMediaType)
    {
        $customerData = $this->getCustomerData($dataUser, $userid, $socialMediaType);
        if ($customerData) {
            $this->setDataInSession($customerData);
        }
    }
}
