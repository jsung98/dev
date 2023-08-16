<?php
/**
 * @author Eguana Team
 * @copyright Copyright (c) 2020 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Abbas Ali Butt
 * Date: 13/01/2023
 * Time: 6:11 PM
 */
namespace Eguana\SocialLogin\Controller\KakaoLogin;

use Eguana\SocialLogin\Helper\Data as Helper;
use Eguana\SocialLogin\Model\SocialLoginHandler as SocialLoginModel;
use Eguana\SocialLogin\Model\SocialLoginRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

/**
 * Class Callback
 *
 * Callback class for kakao login
 */
class Callback extends Action
{
    const SOCIAL_MEDIA_TYPE = 'kakao';

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var SocialLoginModel
     */
    protected $socialLoginModel;

    /**
     * @var Curl
     */
    private $curlClient;

    /**
     * @var SocialLoginRepository
     */
    private $socialLoginRepository;

    /**
     * Callback constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param Helper $helper
     * @param SocialLoginModel $socialLoginModel
     * @param Curl $curl
     * @param SocialLoginRepository $socialLoginRepository
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Helper $helper,
        SocialLoginModel $socialLoginModel,
        Curl $curl,
        SocialLoginRepository $socialLoginRepository
    ) {
        $this->helper                           = $helper;
        $this->socialLoginModel                 = $socialLoginModel;
        $this->curlClient                       = $curl;
        $this->socialLoginRepository            = $socialLoginRepository;
        parent::__construct(
            $context,
        );
    }

    /**
     * Kakao callback function
     * @return ResponseInterface|Controller\ResultInterface|null
     */
    public function execute()
    {
        $socialMediaType = self::SOCIAL_MEDIA_TYPE;
        $code = $this->getRequest()->getParam('code');
        $state = $this->getRequest()->getParam('state');
        $client_id = $this->helper->getKakaoClientId();
        $client_secret = $this->helper->getAppSecret();
        $redirect_uri = $this->helper->getKakaoCallbackUrl();
        if ($this->socialLoginModel->getCoreSession()->getKakaoLoginState() != $state) {
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account/login');
            return $resultRedirect;
        }
        $this->socialLoginModel->getCoreSession()->unsKakaoLoginState();
        $response = $this->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
        try {
            $access_token = isset($response['access_token']) ? $response['access_token'] : '';

        } catch (\Exception $e) {
            $this->getResponse()->setBody(__($e->getMessage()));
            return null;
        }
        try {
            $response = $this->getKakaoUserProfile($access_token, $client_id, $redirect_uri);
        } catch (\Exception $e) {
            $this->getResponse()->setBody(__($e->getMessage()));
            return null;
        }
        $userid = isset($response['id']) ? $response['id'] : '';
        $name = isset($response["kakao_account"]["profile"]["nickname"]) ? $response["kakao_account"]["profile"]["nickname"] : '';
        $email = isset($response["kakao_account"]["email"]) ? $response["kakao_account"]["email"] : '';

        $customerId = $this->socialLoginRepository->getSocialMediaCustomer($userid, $socialMediaType);
        //If customer exists then login and close popup else close pop and redirect to social login page
        if ($customerId) {
            $this->socialLoginModel->getCoreSession()->unsSocialCustomerId();
            $this->socialLoginModel->getCoreSession()->setSocialCustomerId($customerId);
            $this->socialLoginModel->getCoreSession()->unsSocialmediaType();
            $this->socialLoginModel->getCoreSession()->setSocialmediaType('kakao');
            $this->socialLoginModel->getCoreSession()->unsSocialmediaId();
            $this->socialLoginModel->getCoreSession()->setSocialmediaId($userid);
        } else {
            $this->socialLoginModel->redirectCustomer($response, $userid, $socialMediaType);
        }
        if ($this->helper->isMobile()) {
            $url = $this->_url->getUrl('sociallogin/login/validatelogin',
                [
                    'customer_id'=> $customerId,
                    'socialmedia_type'=> "kakao",
                    'social_id'=> $userid,
                    'name'=> $name,
                    'email'=> $email
                ]
            );
            $this->_redirect($url);
        } else {
            $this->helper->closePopUpWindow($this);
        }
    }

    /**
     * @return Curl
     */
    private function getCurlClient()
    {
        return $this->curlClient;
    }

    /**
     * Get kakao user access token
     * @param $client_id
     * @param $client_secret
     * @param $redirect_uri
     * @param $code
     * @return mixed|null
     */
    private function getAccessToken($client_id, $client_secret, $redirect_uri, $code)
    {
        $response = null;
        try {
            $apiUrl = "https://kauth.kakao.com/oauth/token";
            $request = 'grant_type=' . 'authorization_code';
            $request .= '&client_id=' . $client_id;
            $request .= '&redirect_uri=' . $redirect_uri;
            $request .= '&code=' . $code;
            $this->getCurlClient()->setOptions(
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
                    ]
                ]
            );
            $this->getCurlClient()->get($apiUrl, []);
            $status = $this->getCurlClient()->getStatus();
            if (($status == 400 || $status == 401)) {
                $message = __('Unspecified OAuth error occurred.');
                $this->getResponse()->setBody(__($message));
                return null;
            }
            return json_decode($this->getCurlClient()->getBody(), true);
        } catch (\Exception $e) {
            $this->getResponse()->setBody(__($e->getMessage()));
            return null;
        }
    }

    /**
     * Get kakao user profile
     * @param $access_token
     * @param $client_id
     * @param $redirect_uri
     * @return mixed|null
     */
    private function getKakaoUserProfile($access_token, $client_id, $redirect_uri)
    {
        $url = 'https://kapi.kakao.com/v2/user/me';

        try {
            $this->getCurlClient()->setOptions(
                [
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $access_token,
                    ]
                ]
            );
            $this->getCurlClient()->get($url, []);
            $status = $this->getCurlClient()->getStatus();
            if ($status != 200) {
                $message = __('Unspecified OAuth error occurred.');
                $this->getResponse()->setBody(__($message));
            }
            return json_decode($this->getCurlClient()->getBody(), true);
        } catch (\Exception $e) {
            $this->getResponse()->setBody(__($e->getMessage()));
            return null;
        }
    }
}
