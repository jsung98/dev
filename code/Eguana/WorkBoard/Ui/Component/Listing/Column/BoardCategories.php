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

namespace Eguana\WorkBoard\Ui\Component\Listing\Column;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterfaceAlias;
use Magento\Framework\App\RequestInterface;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Magento\Store\Ui\Component\Listing\Column\Store\Options;
use Eguana\WorkBoard\Model\BoardConfiguration\BoardConfiguration;
use Psr\Log\LoggerInterface;

/**
 * This class is used to add show the available categories of Board
 *
 * Class BoardCategories
 */
class BoardCategories extends Options
{
    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var StoreManagerInterfaceAlias
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * boardCategories constructor.
     *
     * @param Json $json
     * @param BoardRepositoryInterface $boardRepository
     * @param RequestInterface $request
     * @param BoardConfiguration $boardConfiguration
     * @param StoreManagerInterfaceAlias $storeManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        Json $json,
        BoardRepositoryInterface $boardRepository,
        RequestInterface $request,
        BoardConfiguration $boardConfiguration,
        StoreManagerInterfaceAlias $storeManager,
        LoggerInterface $logger
    ) {
        $this->boardConfiguration = $boardConfiguration;
        $this->json = $json;
        $this->logger = $logger;
        $this->boardRepository = $boardRepository;
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * Get categories options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $storeCategoryList = [];
        try {
            $isStoreIdZero = false;
            $id = $this->request->getParam('board_id');
            if (isset($id)) {
                $board = $this->boardRepository->getById($this->request->getParam('board_id'));
                if ($board['store_id'][0] == 0) {
                    $isStoreIdZero = true;
                }
            }
            $categoryList = [];
            $storeId[] = [];
            $param = 0;
            if (!isset($board) || $isStoreIdZero) {
                $storeManagerDataList = $this->storeManager->getStores();
                foreach ($storeManagerDataList as $key => $value) {
                    $storeId[] = $key;
                }
            } else {
                $storeId = $board['store_id'];
                $param = 1;
            }
            $i = 0;
            $index = 0;
            foreach ($storeId as $key) {
                if ($i == 0 && $param == 0) {
                    $i++;
                    continue;
                } else {
                    $id = $key;
                }
                $result = $this->boardConfiguration->getCategory('category', $id);
                $categoryId = 0;
                if (count($result) > 0) {
                    foreach ($result as $category) {
                        $categoryList[$index][] = [
                            'label' => $category,
                            'value' => $id . '.' . $categoryId
                        ];
                        $categoryId++;
                    }
                    $storeCategoryList[$index] = [
                        'label'   => $this->storeManager->getStore($id)->getName(),
                        'value'   => $categoryList[$index]
                    ];
                    $index++;
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $storeCategoryList;
    }

    /**
     * Get categories of Board fron configuration
     *
     * @return array
     */
    public function getCategories()
    {
        $result = $this->boardConfiguration->getConfigValue('category');
        $category = [];
        if (isset($result)) {
            $categories = $this->json->unserialize($result);
            foreach ($categories as $value) {
                $category[$value['attribute_name']] = $value['attribute_name'];
            }
        }
        return $category;
    }
}
