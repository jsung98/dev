<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 3/12/21
 * Time: 3:06 PM
 */
namespace Eguana\Contactus\Model\Contactus;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Eguana\Contactus\Model\ResourceModel\Contactus\CollectionFactory as ContactusCollectionFactory;
use Eguana\Contactus\Model\Config\Source\Type as ProjectType;

/**
 * This class is responsible to provide data to contact us grid
 *
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{

    protected ProjectType $projectType;

    protected array $loadedData;

    protected RequestInterface $_request;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ContactusCollectionFactory $collectionFactory
     * @param RequestInterface $_request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ContactusCollectionFactory $collectionFactory,
        RequestInterface $_request,
        ProjectType $projectType,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->_request = $_request;
        $this->projectType = $projectType;
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
        foreach ($items as $contactus) {
            $projectTypesListing = $this->projectType->getType();
            $types = explode(',', $contactus->getData('type'));
            $typeNamesArray = [];
            foreach ($types as $type) {
                $typeNamesArray[] = $projectTypesListing[$type];
            }
            $this->loadedData[$contactus->getId()] = $contactus->getData();
            $this->loadedData[$contactus->getId()]['type'] = implode(',', $typeNamesArray);
        }
        return $this->loadedData;
    }
}
