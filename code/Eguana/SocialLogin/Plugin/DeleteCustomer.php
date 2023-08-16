<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: sultan
 * Date: 1/25/23
 * Time: 12:03 PM
 */


namespace Eguana\SocialLogin\Plugin;

use Eguana\SocialLogin\Model\SocialLoginRepository;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Customer\Model\SessionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Customer\Model\Session;

/**
 * delete customer and unlink kakao app
 * Class DeleteCustomer
 */
class DeleteCustomer extends Action
{
    const APP_ADMIN_KEY = 'SocialLogin/kakao/kakao_unlink/app_admin_key';
    const BASE_URL = 'SocialLogin/kakao/kakao_unlink/base_url';
    const SYNC_UNLINK_URL = '/v1/user/unlink';
    const USER_ID = 'user_id';

    /**
     * @var Redirect
     */
    private $redirectFactory;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var CustomerRepository
     */
    private $customerRepo;

    /**
     * @var ManagerInterface
     */
    private $message;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var LoggerInterface
     */
    private $logger;


    /**
     * @var SocialLoginRepository
     */
    private $socialLoginRepository;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param SessionFactory $customerSession
     * @param CustomerRepository $customerRepository
     * @param ManagerInterface $message
     * @param UrlInterface $url
     * @param LoggerInterface $logger
     * @param SocialLoginRepository $socialLoginRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Curl $curl
     * @param Json $json
     */
    public function __construct(
        Context               $context,
        RedirectFactory       $redirectFactory,
        SessionFactory        $customerSession,
        CustomerRepository    $customerRepository,
        ManagerInterface      $message,
        UrlInterface          $url,
        LoggerInterface       $logger,
        SocialLoginRepository $socialLoginRepository,
        ScopeConfigInterface  $scopeConfig,
        Curl                  $curl,
        Json                  $json
    )
    {
        $this->redirectFactory = $redirectFactory->create();
        $this->customerSession = $customerSession->create();
        $this->customerRepo = $customerRepository;
        $this->message = $context->getMessageManager();
        $this->url = $url;
        $this->logger = $logger;
        $this->socialLoginRepository = $socialLoginRepository;
        $this->scopeConfig = $scopeConfig;
        $this->curl = $curl;
        $this->json = $json;
        return parent::__construct($context);
    }
    public function beforeDeleteById(){
        $customerId = $this->getRequest()->getParam('id');
        if ($customerId) {
            try {
                $customer = $this->customerRepo->getById($customerId);
                $customer->setCustomAttribute('is_secessioned', 1);
                if ($customer) {
                    $socialLoginUser = $this->socialLoginRepository->getSocialLoginUser($customerId);
                    $socialLoginId = $socialLoginUser->getData('social_id');
                    if ($socialLoginId && $this->getBaseUrl() && $this->getAppAdminKey()) {
                        $this->getUnlinkByKakao($socialLoginId);
                        $this->customerRepo->save($customer);
                        $this->message->addSuccessMessage(__('Thank you for using our services'));
                    }
                    $this->socialLoginRepository->deleteByCustomerId($customerId);
                    $this->customerSession->logout();
                } else {
                    $this->message->addErrorMessage(__("we can't implement secession. please try again later"));
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }
        return ;

    }


    /**
     * function is for implementing secession
     * this will change custom attribute which takes record either customer is secessioned or not
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {

    }

    /**
     * kakao unlink api function
     * @param $socialLoginId
     * @return mixed|null
     */
    public function getUnlinkByKakao($socialLoginId)
    {
        $appAdminKey = $this->getAppAdminKey();
        $response = null;
        try {
            $apiUrl = $this->getBaseUrl() . self::SYNC_UNLINK_URL;
            $request = 'target_id_type=' . self::USER_ID;
            $request .= '&target_id=' . $socialLoginId;
            $this->curl->setOptions(
                [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => $request,
                    CURLOPT_HEADER => false,
                    CURLOPT_VERBOSE => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/x-www-form-urlencoded',
                        'Authorization: KakaoAK ' . $appAdminKey,
                    ]
                ]
            );
            $this->curl->get($apiUrl, []);
            $status = $this->curl->getStatus();
            $response = $this->curl->getBody();
            if (($status == 400 || $status == 401)) {
                $message = __('Unspecified OAuth error occurred.');
                $this->getResponse()->setBody(__($message));
                return null;
            }
            return $this->json->unserialize($response, true);
        } catch (\Exception $e) {
            $this->getResponse()->setBody(__($e->getMessage()));
            return null;
        }
    }

    /**
     * get base url
     */
    public function getBaseUrl()
    {
        return $this->scopeConfig->getValue(
            self::BASE_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get app admin key
     */
    public function getAppAdminKey()
    {
        return $this->scopeConfig->getValue(
            self::APP_ADMIN_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }
}


