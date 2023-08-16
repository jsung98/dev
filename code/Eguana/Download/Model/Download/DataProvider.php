<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 3/12/21
 * Time: 3:06 PM
 */
namespace Eguana\Download\Model\Download;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Eguana\Download\Model\ResourceModel\Download\CollectionFactory as DownloadCollectionFactory;

/**
 * This class is responsible to provide data to contact us grid
 *
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{


    protected array $loadedData;

    protected RequestInterface $_request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param DownloadCollectionFactory $collectionFactory
     * @param RequestInterface $_request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DownloadCollectionFactory $collectionFactory,
        RequestInterface $_request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->_request = $_request;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $this->loadedData = [];
        $items = $this->collection->getItems();
        foreach ($items as $download) {
            $this->loadedData[$download->getId()] = $download->getData();
        }
        return $this->loadedData;
    }

}
