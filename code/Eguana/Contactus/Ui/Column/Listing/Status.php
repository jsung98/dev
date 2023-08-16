<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 8/12/21
 * Time: 3:38 PM
 */
namespace Eguana\Contactus\Ui\Column\Listing;

use Eguana\Contactus\Model\Config\Source\StatusOption as StatusOptions;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Status use as a data source for status options
 * Class Status
 */
class Status extends Column
{
    private StatusOptions $statusOption;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StatusOptions $statusOption
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StatusOptions $statusOption,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->statusOption = $statusOption;
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $Statuses = $this->statusOption->getStatuses();
            foreach ($dataSource['data']['items'] as &$item) {
                $item['status'] = $Statuses[$item['status']];
            }
        }

        return $dataSource;
    }
}
