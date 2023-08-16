<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 6/12/21
 * Time: 5:35 PM
 */
namespace Eguana\Download\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Download module configuration
 */
class Config
{
    const XML_PATH_DOWN_ENABLED = 'download/generalconfig/enabled';

    const XML_PATH_DOWN_VALUE = 'download/generalconfig/upload';


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
    public function isEnabled() :bool
    {
        return boolval($this->scopeConfig->getValue(self::XML_PATH_DOWN_ENABLED, ScopeInterface::SCOPE_STORE));
    }

    /**
     * Get Config Value
     * @return mixed
     */
    public function isUpload()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DOWN_VALUE, ScopeInterface::SCOPE_STORE);
    }
}
