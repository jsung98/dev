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

namespace Eguana\WorkBoard\Model;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Model\ResourceModel\Board as BoardResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

/**
 * This model class is used for the Curd operation of Board
 *
 * Class Board
 */
class Board extends AbstractModel implements BoardInterface, IdentityInterface
{
    public const CACHE_TAG = 'eguana_board';
    public const STATUS_ENABLED = 1;
    public const STATUS_DISABLED = 0;

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Constructor to initialize ResourceModel
     */
    protected function _construct()
    {
        $this->_init(BoardResourceModel::class);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * Set Board Id
     *
     * @param int $board_id
     * @return $this
     */
    public function setId($board_id)
    {
        return $this->setData(self::BOARD_ID, $board_id);
    }

    /**
     * Retrieve Board Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::BOARD_ID);
    }

    /**
     * Set Board Position
     *
     * @param int $position
     * @return $this
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Retrieve Board Position
     *
     * @return int
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }
    /**
     * Set Board Title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Retrieve Board Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }
    /**
     * Set Board subTitle
     *
     * @param string $subtitle
     * @return $this
     */
    public function setSubTitle($subtitle)
    {
        return $this->setData(self::TITLE, $subtitle);
    }

    /**
     * Retrieve Board Title
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->getData(self::SUBTITLE);
    }

    /**
     * Set Board Description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Retrieve Board Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Set Board Thumbail
     *
     * @param string $thumbnailImage
     * @return $this
     */
    public function setThumbnailImage($thumbnailImage)
    {
        return $this->setData(self::THUMBNAIL_IMAGE, $thumbnailImage);
    }

    /**
     * Retrieve Board Thumbail by Mobile
     *
     * @return string
     */
    public function getMobileThumbnailImage()
    {
        return $this->getData(self::MOBILE_THUMBNAIL_IMAGE);
    }
    /**
     * Set Board Thumbailby Mobile
     *
     * @param string $mobilethumbnailImage
     * @return $this
     */
    public function setMobileThumbnailImage($mobilethumbnailImage)
    {
        return $this->setData(self::MOBILE_THUMBNAIL_IMAGE, $mobilethumbnailImage);
    }

    /**
     * Retrieve Board Thumbail
     *
     * @return string
     */
    public function getThumbnailImage()
    {
        return $this->getData(self::THUMBNAIL_IMAGE);
    }


    /**
     * Get Url Key
     *
     * @return string
     */
    public function getIdentifier() : string
    {
        return $this->getData(self::IDENTIFIER);
    }

    /**
     * Set Url Key
     *
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * Get Meta Title
     *
     * @return string|null
     */
    public function getMetaTitle() : string
    {
        return $this->getData(self::META_TITLE);
    }

    /**
     * Set Meta Title
     *
     * @param string $metaTitle
     * @return $this
     */
    public function setMetaTitle($metaTitle)
    {
        return $this->setData(self::META_TITLE, $metaTitle);
    }

    /**
     * Get Meta Keyword
     *
     * @return string|null
     */
    public function getMetaKeywords() : string
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * Set Meta Keyword
     *
     * @param string $metaKeywords
     * @return $this
     */
    public function setMetaKeyowrds($metaKeywords)
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * Get Meta Description
     *
     * @return string|null
     */
    public function getMetaDescription()
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * Set Meta Description
     *
     * @param string $metaDescription
     * @return $this
     */
    public function setMetaDescription($metaDescription)
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * Retrieve Category
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->getData(self::CATEGORY);
    }

    /**
     * Set Category
     *
     * @param string $category
     * @return BoardInterface
     */
    public function setCategory($category)
    {
        return $this->setData(self::CATEGORY, $category);
    }

    /**
     * Set Board Creation Time
     *
     * @param string $creation_time
     * @return $this
     */
    public function setCreationTime($creation_time)
    {
        return $this->setData(self::CREATION_TIME, $creation_time);
    }

    /**
     * Retrieve Board Creation Time
     *
     * @return string
     */
    public function getCreationTime()
    {
        return $this->getData(self::CREATION_TIME);
    }

    /**
     * Set Board Update Time
     *
     * @param string $update_time
     * @return $this
     */
    public function setUpdateTime($update_time)
    {
        return $this->setData(self::UPDATE_TIME);
    }

    /**
     * Retrieve Board Update Time
     *
     * @return string
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    /**
     * Set Board Status
     *
     * @param string $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Retrieve Board Status
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set Board Status
     *
     * @param string $mainActive
     * @return $this
     */
    public function setMainActive($mainActive)
    {
        return $this->setData(self::MAIN_ACTIVE, $mainActive);
    }

    /**
     * Retrieve Board Status
     *
     * @return string
     */
    public function getMainActive()
    {
        return $this->getData(self::MAIN_ACTIVE);
    }


    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : $this->getData('store_id');
    }

    /**
     * Prepare Board statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Before Save method
     *
     * @return Board
     */
    public function beforeSave()
    {
        if ($this->hasDataChanges()) {
            $this->setUpdateTime(null);
        }

        return parent::beforeSave();
    }
}
