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

namespace Eguana\WorkBoard\Block\Index;

use Eguana\WorkBoard\Model\BoardConfiguration\BoardConfiguration;
use Eguana\WorkBoard\Model\ResourceModel\Board\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
//use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Theme\Block\Html\Pager;
use Psr\Log\LoggerInterface;

/**
 * Class for listing of WorkBoard
 *
 * Class Index
 */
class Index extends Template
{
    /**
     * @var DateTime
     */
 //   private $date;

    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var BoardInterface
     */
    private $logger;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @var CollectionFactory
     */
    private $boardCollection;

    /**
     * Index constructor.
     *
     * @param Context $context
     //* @param DateTime $date
     * @param CollectionFactory $boardCollection
     * @param StoreManagerInterface $storeManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param LoggerInterface $logger
     * @param SortOrderBuilder $sortOrderBuilder
     * @param BoardConfiguration $boardConfiguration
     */
    public function __construct(
        Context $context,
        //DateTime $date,
        CollectionFactory $boardCollection,
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LoggerInterface $logger,
        SortOrderBuilder $sortOrderBuilder,
        BoardConfiguration $boardConfiguration
    ) {
        $this->boardCollection = $boardCollection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->boardConfiguration = $boardConfiguration;
        parent::__construct($context);
    }

    /**
     * Prepare layout
     *
     * @return $this|Index
     */
    protected function _prepareLayout()
    {
        try {
            parent::_prepareLayout();
            if ($this->getBoardCollection()) {
                $this->getBoardCollection()->load();
            }
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }
        parent::_prepareLayout();
        return $this;
    }

    /**
     * Get Pager Html
     *
     * @return string
     */
    public function getPagerHtml() : string
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get collection of Board using repository
     *
     * @return mixed
     */
    public function getBoardCollection()
    {
        $collection = [];
        try {
            $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
            $board = $this->getPerPageBoard();
            $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : $board;
            $currentStoreId = $this->storeManager->getStore()->getId();
            $collection = $this->boardCollection->create();
            $collection->addFieldToFilter('is_active', ['eq' => '1']);
            $collection->addStoreFilter($currentStoreId);
            $collection->setOrder('position', 'asc');
            $collection->setPageSize($pageSize);
            $collection->setCurPage($page);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $collection;
    }

    /**
     * Get sort order of board from configuration
     *
     * @return string
     */
    private function getSortOrder()
    {
        return $this->boardConfiguration->getConfigValue('sort_order');
    }

    /**
     * Get no of recorde show on per page
     *
     * @return int
     */
    public function getPerPageBoard()
    {
        return $this->boardConfiguration->getConfigValue('per_page_board');
    }

    /**
     * Get array of pagination
     *
     * @return array
     */
    public function getpagenationArray()
    {
        $board = $this->getPerPageBoard();
        $result = [];
        for ($i = 1; $i < 5; $i++) {
            $result[$board * $i] = $board * $i;
        }
        return $result;
    }

    /**
     * Get date of board
     *
     * @param mixed $dateTime
     * @return string
     *
    public function getBoardDate($dateTime)
    {
        return $this->date->creation_time('Y.m.d', strtotime($dateTime));
    }

    /**
     * Get url of board ddetail page
     *
     * @param string $identifier
     * @return string
     */
    public function getBoardDetailUrl($identifier)
    {
        $Url = '';
        try {
            $Url = $this->storeManager->getStore()->getBaseUrl();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $Url . $identifier;
    }

    /**
     * Get Image Url
     *
     * @param string $url
     * @return string
     */
    public function getImageUrl($url, $thumbnailURL = '')
    {
        if(!$url) {
            return $this->getThumbnailImage($thumbnailURL);
        }
        $mediaUrl = '';
        try {
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        if($url == ''){
            $src = $mediaUrl . $thumbnailURL;
        }else {
            $src = $mediaUrl . $url;
        }
        if ($src == $url){
            return $this->getThumbnailImage($thumbnailURL);
        }else {
            return $src;
        }
    }

    public function getThumbnailImage($url)
    {
        try {
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $mediaUrl . $url;
    }

    public function getMobileThumbnailImage($url)
    {
        $mediaUrl = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            try {
                $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        return $mediaUrl . $url;
    }
    /**
     * Get category name
     *
     * @param array $categoriesId
     * @return mixed|string
     */
    public function getCategoryName(array $categoriesId)
    {
        try {
            $storeId = $this->storeManager->getStore()->getId();
            foreach ($categoriesId as $value) {
                $categoryStoreId = explode('.', $value);
                $categoryStoreId2 = explode(',', $value);
                $categories = $this->boardConfiguration->getCategory('category', $categoryStoreId[0]);
                if ($storeId == $categoryStoreId[0]) {
                    if (count($categoryStoreId2)>1 && isset($categoryStoreId[0])) {
                        $newArr = array_map(function ($item) use ($categoryStoreId) {
                            return str_replace($categoryStoreId[0] . ".", '', $item);
                        }, $categoryStoreId2);
                        if (isset($categories)) {
                            $filteredCategories = array_intersect_key($categories, array_flip($newArr));
                            $categoryName = implode(',', $filteredCategories);
                        }
                    }else{
                        if (isset($categories)) {
                            if (isset($categories[$categoryStoreId[1]])) {
                                $categoryName =  $categories[$categoryStoreId[1]];
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $categoryName;
    }
    /**
     * Get array of categories
     *
     * @return array
     */
    public function getCategory()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $categories = $this->boardConfiguration->getCategory('category', $storeId);

        return $categories;
    }

}
