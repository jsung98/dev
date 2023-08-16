<?php
/**
 * @author Eguana Team
 * @copyright Copyright (c) 2020 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Abbas Ali Butt
 * Date: 12/01/2023
 * Time: 5:50 PM
 */
namespace Eguana\SocialLogin\Block\SocialLogin;

use Eguana\SocialLogin\Helper\Data;
use Eguana\SocialLogin\Model\SocialLoginHandler as SocialLoginModel;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class Login
 *
 * Class for showing social logins
 */
class Login extends Template
{

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var SocialLoginModel
     */
    private $socialLoginModel;

    /**
     * Login constructor.
     * @param Context $context
     * @param Data $helper
     * @param SocialLoginModel $socialLoginModel
     */
    public function __construct(
        Context $context,
        SocialLoginModel $socialLoginModel,
        Data $helper
    ) {
        $this->helper           = $helper;
        $this->socialLoginModel = $socialLoginModel;
        parent::__construct($context);
    }

    /**
     * Save state to registry
     * @return string
     */
    public function getState()
    {
        $state = hash('sha256', uniqid(rand(), true));
        $this->socialLoginModel->getCoreSession()->start();
        return $state;
    }

    /**
     * Unset Session before login
     */
    public function unSetSession()
    {
        $this->socialLoginModel->getCoreSession()->start();
        $this->socialLoginModel->getCoreSession()->unsSocialUserData();
    }

    /**
     * Get helper
     * @return Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

}
