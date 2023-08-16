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
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Session\Generic;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller;

/**
 * Class Connect
 *
 * Kakao callback
 */
class Connect implements ActionInterface
{
    const OAUTH2_AUTH_URI = 'https://kauth.kakao.com/oauth/authorize';

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @var SocialLoginModel
     */
    private $socialLoginModel;
    /**
     * @var ResultFactory
     */
    private $resultFactory;
    /**
     * @var Generic
     */
    private $session;

    /**
     * Connect constructor.
     * @param Context $context
     * @param Helper $helper
     * @param Generic $session
     * @param SocialLoginModel $socialLoginModel
     */
    public function __construct(
        Context $context,
        Helper $helper,
        Generic $session,
        SocialLoginModel $socialLoginModel,
        ResultFactory $resultFactory
    )
    {
        $this->helper = $helper;
        $this->session = $session;
        $this->socialLoginModel = $socialLoginModel;
        $this->resultFactory = $resultFactory;

    }

    /**
     * Redirect to callback
     * @return ResponseInterface|Controller\ResultInterface
     */
    public function execute()
    {
        $state = hash('sha256', uniqid(rand(), true));
        $this->socialLoginModel->getCoreSession()->setKakaoLoginState($state);
        $kakaoAppId = $this->helper-> getKakaoClientId();
        $kakaoRedirectUrl = $this->helper->getKakaoCallbackUrl();
        $url = self::OAUTH2_AUTH_URI . '?' . http_build_query(
                [
                    'client_id' => $kakaoAppId,
                    'redirect_uri' => $kakaoRedirectUrl,
                    'response_type' => 'code',
                    'state' => $state
                ]
            );
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath($url);
    }
}
