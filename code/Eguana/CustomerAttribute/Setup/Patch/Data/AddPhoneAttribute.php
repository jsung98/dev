<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eguana\CustomerAttribute\Setup\Patch\Data;

use Magento\Catalog\Ui\DataProvider\Product\ProductCollectionFactory;
use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\Attribute\CollectionFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AddPhoneAttribute
 * @package Eguana\CustomerAttribute\Setup\Patch\Data
 */
class AddPhoneAttribute implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var CollectionFactory
     */
    private $attributeCollectionFactory;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Attribute
     */
    private $attributeResource;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * AddPhoneAttribute constructor.
     * @param EavSetupFactory $eavSetupFactory
     * @param Config $eavConfig
     * @param LoggerInterface $logger
     * @param CollectionFactory $attributeCollectionFactory
     * @param \Magento\Customer\Model\ResourceModel\Attribute $attributeResource
     * @param ScopeConfigInterface $scopeConfig
     * @param TimezoneInterface $timezone
     * @param ProductCollectionFactory $productCollectionFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig,
        LoggerInterface $logger,
        CollectionFactory $attributeCollectionFactory,
        \Magento\Customer\Model\ResourceModel\Attribute $attributeResource,
        ScopeConfigInterface $scopeConfig,
        TimezoneInterface $timezone,
        ProductCollectionFactory $productCollectionFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->logger = $logger;
        $this->attributeCollectionFactory = $attributeCollectionFactory;
        $this->attributeResource = $attributeResource;
        $this->scopeConfig = $scopeConfig;
        $this->timezone = $timezone;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * {@inheritdoc}
     * @throws LocalizedException
     * @throws NoSuch

     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->addPhoneAttribute();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function addPhoneAttribute()
    {
        $eavSetup = $this->eavSetupFactory->create();


        $eavSetup->addAttribute(
            \Magento\Customer\Model\Customer::ENTITY,
            'phone_number',
            [
                'type' => 'varchar',
                'label' => 'Phone Number',
                'input' => 'text',
                'required' => true,
                'visible' => 1,
                'user_defined' => 1,
                'sort_order' => 997,
                'position' => 997,
                'system' => 0
            ]
        );

        $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);

        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'phone_number');
        $attribute->setData('attribute_set_id', $attributeSetId);
        $attribute->setData('attribute_group_id', $attributeGroupId);

        $attribute->setData('used_in_forms', [
            'adminhtml_customer',
            'customer_account_create',
            'customer_account_edit'

        ]);

        $this->attributeResource->save($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     *
     */
    public function revert()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}