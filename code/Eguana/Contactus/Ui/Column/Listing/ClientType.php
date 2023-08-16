<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 7/12/21
 * Time: 2:55 PM
 */
namespace Eguana\Contactus\Ui\Column\Listing;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Eguana\Contactus\Model\Config\Source\Type as Type;

class ClientType extends Column
{
    /** Url path */
    const URL_PATH_DELETE = 'eguanacontact/contactform/delete';

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;
    private Type $type;

    /**
     * IconActions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param Type $type
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        Type $type,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $Statuses = $this->type->getType();
            foreach ($dataSource['data']['items'] as &$item) {
                $types = explode(',', $item['type']);
                $typeNamesArray = [];
                foreach ($types as $type) {
                    $typeNamesArray[] = $Statuses[$type];
                }
                $item['type'] = implode(',', $typeNamesArray);
            }
        }
        return $dataSource;
    }
}
