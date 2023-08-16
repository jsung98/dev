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

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Psr\Log\LoggerInterface;

/**
 * This class is used to show thumbnails in the Admin Grid
 *
 * Class Thumbnail
 */
class Thumbnail extends Column
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * constant
     */
    public const ALT_FIELD = 'title';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Thumbnail constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                if (!array_key_exists($fieldName, $item)) {
                    continue;
                }

                $url = '';
                if ($item[$fieldName] != '') {
                    try {
                        $url = $this->storeManager->getStore()
                                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . $item[$fieldName];
                    } catch (\Exception $e) {
                        $this->logger->error($e->getMessage());
                    }
                }
                if ($url == '') {
                    $url = $this->storeManager->getStore()
                            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/placeholder/image.jpg';
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';

            }
        }

        return $dataSource;
    }
    /**
     * Get Alt
     *
     * @param array $row
     * @return null
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
