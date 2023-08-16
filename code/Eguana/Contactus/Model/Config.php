<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 6/12/21
 * Time: 5:35 PM
 */
namespace Eguana\Contactus\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Contact module configuration
 */
class Config
{
    const XML_PATH_EMAIL_ENABLED = 'contact/email/enabled';

    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Config Value
     * @return mixed
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_EMAIL_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}
