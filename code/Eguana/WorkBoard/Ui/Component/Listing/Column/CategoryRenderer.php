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

use Eguana\WorkBoard\Model\BoardConfiguration\BoardConfiguration;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Psr\Log\LoggerInterface;

/**
 * Class for displaying Store views in Grid
 *
 * Class StoreRenderer
 *
 */
class CategoryRenderer extends Column
{
    /**
     * @var BoardConfiguration
     */
    private $boardConfiguration;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * CategoryRenderer constructor.
     * @param ContextInterface $context
     * @param StoreManagerInterface $storeManager
     * @param UiComponentFactory $uiComponentFactory
     * @param BoardConfiguration $boardConfiguration
     * @param LoggerInterface $logger
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        StoreManagerInterface $storeManager,
        UiComponentFactory $uiComponentFactory,
        BoardConfiguration $boardConfiguration,
        LoggerInterface $logger,
        array $components = [],
        array $data = []
    ) {
        $this->boardConfiguration = $boardConfiguration;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!isset($item['category'])) {
                    continue;
                }
                $name = $item['category'];
                $item['category'] = $this->prepareItem($name);
            }
        }

        return $dataSource;
    }

    /**
     * Prepare Store views data source
     *
     * @param array $name
     * @return string
     */
    protected function prepareItem(array $name)
    {
        $content = '';
        try {
            foreach ($name as $value) {
                $categoryStoreId = explode('.', $value);
                $categoryStoreId2 = explode(',', $value);
                $categories = $this->boardConfiguration->getCategory('category', $categoryStoreId[0]);
                if (count($categoryStoreId2) > 1 && isset($categoryStoreId[0])) {
                    $newArr = array_map(function ($item) use ($categoryStoreId) {
                        return str_replace($categoryStoreId[0] . ".", '', $item);
                    }, $categoryStoreId2);
                    if (isset($categories)) {
                        $content .= "<b>" . $this->storeManager->getStore($categoryStoreId[0])->getName() . "</b><br/>";
                        $filteredCategories = array_intersect_key($categories, array_flip($newArr));
                        foreach ($filteredCategories as $category) {
                            $content .= $category . "<br>";
                        }
                    }
                }
                else {
                    if (isset($categories)) {
                        $content .= "<b>" . $this->storeManager->getStore($categoryStoreId[0])->getName() . "</b><br/>";
                        if (isset($categories[$categoryStoreId[1]])) {
                            $content .=   $categories[$categoryStoreId[1]] . "<br>";
                        } else {
                            $content .=   '12';
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $content;
    }

}
