<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: sultan
 * Date: 1/23/23
 * Time: 12:03 PM
 */
declare(strict_types=1);

namespace Eguana\SocialLogin\Plugin\Account;

use Magento\Customer\Controller\Account\Logout;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Request\DataPersistorInterface;
use Eguana\SocialLogin\Model\SocialLoginRepository;


class CustomerLogout
{
    const REST_API_KEY = 'SocialLogin/kakao/client_id';
    const LOGOUT_REDIRECT_URI = 'SocialLogin/kakao/url_info/logout_redirect_uri';
    const BASE_URL = 'SocialLogin/kakao/url_info/base_url';
    const SYNC_LOGOUT_URL = '/oauth/logout/callback';
    const DATA_PERSISTS_KEY = 'currentCustomerId';
    const SOCIAL_MEDIA_TYPE = 'kakao';

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var SocialLoginRepository
     */
    private $socialLoginRepository;

    /**
     * @param RedirectInterface $redirect
     * @param ScopeConfigInterface $scopeConfig
     * @param RedirectFactory $resultRedirectFactory
     * @param Session $session
     * @param DataPersistorInterface $dataPersistor
     * @param SocialLoginRepository $socialLoginRepository
     */
    public function __construct(
        RedirectInterface $redirect,
        ScopeConfigInterface $scopeConfig,
        RedirectFactory  $resultRedirectFactory,
        Session $session,
        DataPersistorInterface $dataPersistor,
        SocialLoginRepository $socialLoginRepository
    ){
        $this->redirect = $redirect;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->dataPersistor = $dataPersistor;
        $this->socialLoginRepository = $socialLoginRepository;
    }

    /**
     * after execute to log out from kakao account
     * @param Logout $subject
     * @param $result
     * @return Redirect
     */
    public function afterExecute(Logout $subject, $result)
    {
        $key = self::DATA_PERSISTS_KEY;
        $customerId = $this->dataPersistor->get($key);
        $socialMediaType = self::SOCIAL_MEDIA_TYPE;
        $isCustomerExist = $this->socialLoginRepository->isCustomerExist($customerId, $socialMediaType);
        $clientId = $this->getClientId();
        $logoutRedirectUrl = $this->getLogoutRedirectUrl();
        $baseUrl = $this->getBaseUrl();
        $logoutUrl = $baseUrl . self::SYNC_LOGOUT_URL;
        $logoutUrl .= '?logout_redirect_uri=' . $logoutRedirectUrl;
        $logoutUrl .= '%26client_id=' . $clientId;
        $redirectUrl = $this->redirect->getRefererUrl();
        $resultRedirect = $this->resultRedirectFactory->create();
        if (!$isCustomerExist && $clientId && $logoutRedirectUrl && $logoutUrl) {
            $resultRedirect->setUrl($logoutUrl);
        }
        else {
            $resultRedirect->setPath($redirectUrl);
        }
        return $resultRedirect;
    }

    /**
     * before execute to get customer id
     * @param Logout $subject
     */
    public function beforeExecute(Logout $subject)
    {
        $id = $this->session->getId();
        $key = self::DATA_PERSISTS_KEY;
        $this->dataPersistor->set($key, $id);
    }
    /**
     * get client id
     */
    public function getClientId()
    {
        return $this->scopeConfig->getValue(
            self::REST_API_KEY,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * get logout redirect url
     */
    public function getLogoutRedirectUrl()
    {
        return $this->scopeConfig->getValue(
            self::LOGOUT_REDIRECT_URI,
            ScopeInterface::SCOPE_STORE
        );
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
}
