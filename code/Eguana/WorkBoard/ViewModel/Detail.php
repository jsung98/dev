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

namespace Eguana\WorkBoard\ViewModel;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Eguana\WorkBoard\Model\BoardConfiguration\BoardConfiguration;
use Eguana\WorkBoard\Model\ResourceModel\Board\CollectionFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class Detail
 *  Eguana\WorkBoard\ViewModel
 */
class Detail implements ArgumentInterface
{
    protected $objectManager;
    /**
     * @var CollectionFactory
     */
    private $boardCollection;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * Detail constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param CollectionFactory $boardCollection
     * @param RedirectFactory $redirectFactory
     * @param BoardRepositoryInterface $boardRepository
     * @param DateTime $date
     * @param RequestInterface $request
     * @param BoardConfiguration $boardConfiguration
     * @param LoggerInterface $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        CollectionFactory $boardCollection,
        RedirectFactory $redirectFactory,
        BoardRepositoryInterface $boardRepository,
        DateTime $date,
        RequestInterface $request,
        BoardConfiguration $boardConfiguration,
        LoggerInterface $logger,
        ObjectManagerInterface $objectManager
    ) {
        $this->date = $date;
        $this->boardConfiguration = $boardConfiguration;
        $this->boardCollection = $boardCollection;
        $this->storeManager = $storeManager;
        $this->redirectFactory = $redirectFactory;
        $this->boardRepository = $boardRepository;
        $this->logger = $logger;
        $this->request = $request;
        $this->filterProvider=$filterProvider;
        $this->logger = $logger;
        $this->objectManager = $objectManager;
    }

    /**
     * Get Detail of board from repository using getById method
     *
     * @return BoardInterface|string
     */
    public function getBoardDetail()
    {
        $board = '';
        try {
            $board = $this->boardRepository->getById($this->request->getParam('board_id'));
            $store_id = $this->storeManager->getStore()->getId();
            if (in_array($store_id, $board['store_id']) || in_array('0', $board['store_id'])
                && $board['is_active'] == 1) {
                return $board;
            } else {
                $board = '';
                return $board;
            }

        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            return $board;
        }
        return $board;
    }

    /**
     * Get collection of notice using repository
     *
     * @return mixed
     */
    public function getBoardCollection()
    {
        $collection = [];
        try {
            $currentStoreId = $this->storeManager->getStore()->getId();
            $collection = $this->boardCollection->create();
            $collection->addFieldToFilter('is_active', ['eq' => '1']);
            $collection->addFieldToFilter('board_id', ['neq' => $this->request->getParam('board_id')]);
            $collection->addStoreFilter($currentStoreId);
            $collection->setPageSize(4);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $collection;
    }

    /**
     * Get filter provider
     *
     * @param mixed $boarddescription
     * @return string
     */
    public function getBoardDiscription($boarddescription)
    {
        $description = '';
        try {
            return $this->filterProvider->getPageFilter()->filter($boarddescription);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $description;
    }


    /**
     * Get image url
     *
     * @param string $url
     * @return string
     */
    public function getImageUrl($url)
    {
        $mediaUrl = '';
        try {
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $mediaUrl . $url;
    }

    public function getThumbnailImage($url)
    {
        $mediaUrl = $this->objectManager->get('Magento\Store\Model\StoreManagerInterface')
        ->getStore()
        ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $media_dir = '';
        try {
            $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $mediaUrl . $url;
    }

    /**
     * Get base url of website
     *
     * @return string
     */
    public function getDetailPageUrl() : string
    {
        $url = '';
        try {
            $url= $this->storeManager->getStore()->getBaseUrl();
            return $url;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $url;
    }

    /**
     * Get Board Id
     *
     * @param int $board_id
     * @return string
     */
    public function getBoardId($board_id)
    {
        return 'board/index/detail/board_id/' . $board_id;
    }

    /**
     * Get category name
     *
     * @param array $categoriesId
     * @return mixed|string
     */
    public function getCategoryName(array $categoriesId)
    {
        $category = '';
        try {
            $storeId = $this->storeManager->getStore()->getId();
            foreach ($categoriesId as $value) {
                $categoryStoreId = explode('.', $value);
                if ($storeId == $categoryStoreId[0]) {
                    $categories = $this->boardConfiguration->getCategory('category', $categoryStoreId[0]);
                    if (isset($categories[$categoryStoreId[1]])) {
                        $category = $categories[$categoryStoreId[1]];
                    } else {
                        $category = '';
                    }
                    break;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $category;
    }
}
