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

namespace Eguana\WorkBoard\Api\Data;

/**
 * Interface class having getter\setter
 * interface BoardInterface
 */
interface BoardInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const BOARD_ID = 'board_id';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const THUMBNAIL_IMAGE = 'thumbnail_image';
    public const MOBILE_THUMBNAIL_IMAGE = 'mobile_thumbnail_image';
    public const IS_ACTIVE = 'is_active';
    public const MAIN_ACTIVE = 'main_active';
    public const SUBTITLE = 'subtitle';
    public const POSITION = 'position';
    public const IDENTIFIER = 'identifier';
    public const META_TITLE = 'meta_title';
    public const META_KEYWORDS = 'meta_keywords';
    public const META_DESCRIPTION = 'meta_description';
    public const CREATION_TIME = 'creation_time';
    public const UPDATE_TIME = 'update_time';
    /**#@-*/

    /**
     * Set Id
     *
     * @param int $board_id
     * @return BoardInterface
     */
    public function setId($board_id);

    /**
     * Get Id
     *
     * @return int
     */
    public function getId();

    /**
     * Set Position
     *
     * @param int $position
     * @return BoardInterface
     */
    public function setPosition($position);

    /**
     * Get Position
     *
     * @return int
     */
    public function getPosition();

    /**
     * Set title
     *
     * @param string $title
     * @return BoardInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set description
     *
     * @param string $description
     * @return BoardInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set Thumbnail Image
     *
     * @param string $thumbnailImage
     * @return BoardInterface
     */

    public function setThumbnailImage($thumbnailImage);

    /**
     * Get Thumbnail Image
     *
     * @return string
     */
    public function getThumbnailImage();

    /**
     * Set Thumbnail Image by mobile
     *
     * @param string $mobilethumbnailImage
     * @return BoardInterface
     */

    public function setMobileThumbnailImage($mobilethumbnailImage);

    /**
     * Get Thumbnail Image by mobile
     *
     * @return string
     */
    public function getMobileThumbnailImage();

    /**
     * Set Subtitle
     *
     * @param string $subtitle
     * @return BoardInterface
     */
    public function setSubtitle($subtitle);

    /**
     * Get Subtitle
     *
     * @return string
     */
    public function getSubtitle();

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getIdentifier() : string;

    /**
     * Set Identifier
     *
     * @param string $identifier
     * @return BoardInterface
     */
    public function setIdentifier($identifier);

    /**
     * Get Meta Title
     *
     * @return string|null
     */
    public function getMetaTitle() : string;

    /**
     * Set Meta Title
     *
     * @param string $metaTitle
     * @return BoardInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * Get Meta Keyword
     *
     * @return string|null
     */
    public function getMetaKeywords() : string;

    /**
     * Set Meta Keywords
     *
     * @param string $metaKeywords
     * @return BoardInterface
     */
    public function setMetaKeyowrds($metaKeywords);

    /**
     * Get Meta Description
     *
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Set Meta Description
     *
     * @param string $metaDescription
     * @return BoardInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * Set Category
     *
     * @param string $category
     * @return BoardInterface
     */
    public function setCategory($category);

    /**
     * Get Category
     *
     * @return string|null
     */
    public function getCategory();

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return BoardInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return BoardInterface
     */
    public function setUpdateTime($updateTime);

    /**
     * Get Update Time
     *
     * @return mixed
     */
    public function getUpdateTime();

    /**
     * Set Is Active
     *
     * @param string $isActive
     * @return mixed
     */
    public function setIsActive($isActive);

    /**
     * Get Is Active
     *
     * @return mixed
     */
    public function getIsActive();


    /**
     * Set Main Active
     *
     * @param string $mainActive
     * @return mixed
     */
    public function setMainActive($mainActive);

    /**
     * Get Main Active
     *
     * @return mixed
     */
    public function getMainActive();
}
