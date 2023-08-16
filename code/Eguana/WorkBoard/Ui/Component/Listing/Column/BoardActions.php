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

use Magento\Cms\ViewModel\Page\Grid\UrlBuilder;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;

/**
 * This class is used for edit and delete actions functionality
 *
 * Class BoardActions
 */
class BoardActions extends Column
{
    /**
     * Url path for edit
     */
    public const URL_PATH_EDIT = 'board/manage/edit';
    /**
     * Url path for delete
     */
    public const URL_PATH_DELETE = 'board/manage/delete';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var BoardRepositoryInterface
     */
    private $boardRepository;

    /**
     * @var UrlBuilder
     */
    private $scopeUrlBuilder;

    /**
     * boardActions constructor.
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param UrlBuilder $scopeUrlBuilder
     * @param BoardRepositoryInterface $boardRepository
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        UrlBuilder $scopeUrlBuilder,
        BoardRepositoryInterface $boardRepository,
        Escaper            $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->scopeUrlBuilder = $scopeUrlBuilder;
        $this->boardRepository = $boardRepository;
        $this->escaper = $escaper;
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
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['board_id'])) {
                    $title = $this->escaper->escapeHtmlAttr($item['title']);
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'board_id' => $item['board_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'board_id' => $item['board_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete %1', $title),
                                'message' => __('Are you sure you want to delete a %1 record?', $title)
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
