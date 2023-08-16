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

namespace Eguana\WorkBoard\Ui\DataProvider\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Store\Model\StoreManagerInterface;
use Eguana\WorkBoard\Model\ResourceModel\Board\CollectionFactory;
use Eguana\WorkBoard\Model\ResourceModel\Board\Collection;

/**
 * This class is used to get the Data
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Constructor
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $eventManagerCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param StoreManagerInterface $storeManagerInterface
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $eventManagerCollectionFactory,
        DataPersistorInterface $dataPersistor,
        StoreManagerInterface $storeManagerInterface,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $eventManagerCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->storeManager = $storeManagerInterface;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        try {
            if (isset($this->loadedData)) {
                return $this->loadedData;
            }
            $items = $this->collection->getItems();
            /** @var $board */
            foreach ($items as $board) {
                $fields = ['thumbnail_image', 'mobile_thumbnail_image', 'category'];
                foreach ($fields as $field) {
                    $value = $board->getData($field);
                    if (!empty($value)) {
                        if ($field === 'category') {
                            $categories = $board->getData('category')[0];
                            $array_category = explode(',', $categories);
                            $board->setData('category', $array_category);
                        } else {
                            $imagePath = explode('/', $value);
                            $imageCount = count($imagePath);
                            if ($imageCount == 2) {
                                $image = [
                                    'url' => $this->storeManager->getStore()->getBaseUrl('media') . $value,
                                    'file' => $imagePath[$imageCount - 1],
                                ];
                                $board->setData($field, [$image]);
                            }
                        }
                    }
                }
            }

            $this->loadedData[$board->getId()] = $board->getData();

            $data = $this->dataPersistor->get('board_add_form');
            if (!empty($data)) {
                $board = $this->collection->getNewEmptyItem();
                $board->setData($data);
                $this->loadedData[$board->getId()] = $board->getData();
                $this->dataPersistor->clear('board_add_form');
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }
        return $this->loadedData;
    }
}
