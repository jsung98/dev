<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2023 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 12/1/23
 * Time: 4:27 PM
 */

declare(strict_types=1);

namespace Eguana\WorkBoard\Model\BoardConfiguration;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;

/**
 * This class is used to get configuration values from Admin Configuration
 *
 * Class BoardConfiguration
 */
class BoardConfiguration
{
    public const XML_GENERAL_PATH = 'board/generalconfig/';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var Json
     */
    private Json $json;

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * BoardConfiguration constructor.
     *
     * @param Json $json
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Json $json,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->json = $json;
    }

    /**
     * Get Config Value
     *
     * This Method is used to get configuration value on the bases of field parameter
     *
     * @param string|int $field
     * @return int
     */
    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(
            self::XML_GENERAL_PATH . $field,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Config Value
     *
     * This Method is used to get configuration value on the bases of field parameter
     *
     * @param array $field
     * @param array|int|mixed|string $storeId
     * @return array
     */
    public function getCategory($field, $storeId)
    {
        $result =  $this->scopeConfig->getValue(
            self::XML_GENERAL_PATH . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $category = [];
        if (isset($result)) {
            $categories = $this->json->unserialize($result);
            foreach ($categories as $value) {
                $category[] = $value['attribute_name'];
            }
        }
        return $category;
    }

    /**
     * Get Config Value
     *
     * This Method is used to get configuration value on the bases of field parameter
     *
     * @param string $field
     * @param mixed $storeId
     * @return string
     * @throws NoSuchEntityException
     */
    public function getCategoryValue($field, $storeId)
    {
        $category ='';
        if ($storeId[0] == 0) {
            $storeManagerDataList = $this->storeManager->getStores();
            $storeId[] = [];
            foreach ($storeManagerDataList as $key => $value) {
                $storeId[] = $key;
            }
        }
        $i = 0;
        foreach ($storeId as $store) {
            $result = $this->scopeConfig->getValue(
                self::XML_GENERAL_PATH . $field,
                ScopeInterface::SCOPE_STORE,
                $store
            );
            $storename = $this->storeManager->getStore($store)->getName();
            $category .= '<option style="font-weight: bold" disabled data-title="' . $storename. '">'
                        . $storename . '</option>';
                $i++;
            if (!empty($result)) {
                $categories = $this->json->unserialize($result);
                if (count($categories) > 0) {
                    $index = 0;
                    foreach ($categories as $value) {
                        $category .= '<option data-title="' . $value['attribute_name'] . '" value="'
                            . $store . '.' . $index . '">'
                            . $value['attribute_name'] . '</option>';
                        $index++;
                    }
                }
            }
        }
        return $category;
    }
}
