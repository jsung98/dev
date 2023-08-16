<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2022 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Zaid
 * Date: 21/12/22
 * Time: 10:14 AM
 */

declare(strict_types=1);

namespace Eguana\NotifyMe\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Ui\Component\Form;

/**
 * Create Guest Stock Alert Grid On Product Edit Page on Admin
 *
 * Class Stock
 */
class Stock extends AbstractModifier
{
    /**
     * @var string
     */
    private static string $previousGroup = 'related';

    /**
     * @var int
     */
    private static int $sortOrder = 150;
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;
    /**
     * @var LayoutFactory
     */
    private LayoutFactory $layoutFactory;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        LayoutFactory $layoutFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Modify Data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        if (!$this->canShowTab()) {
            return $meta;
        }
        $meta = array_replace_recursive(
            $meta,
            [
                'alerts' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'additionalClasses' => 'admin__fieldset-section',
                                'label' => __('Product Alerts'),
                                'collapsible' => true,
                                'componentType' => Form\Fieldset::NAME,
                                'dataScope' => self::DATA_SCOPE_PRODUCT,
                                'disabled' => false,
                                'sortOrder' => $this->getNextGroupSortOrder(
                                    $meta,
                                    self::$previousGroup,
                                    self::$sortOrder
                                )
                            ],
                        ],
                    ],
                    'children' =>$this->getPanelChildren(),
                ],
            ]
        );

        return $meta;
    }

    /**
     * Get Guest Stock Content
     *
     * @return array[]
     */
    protected function getPanelChildren()
    {
        return [
            'custom_tab_content' => $this->getGuestStockContent()
        ];
    }

    /**
     * Show Guest Stock Content
     *
     * @return array
     */
    protected function getGuestStockContent()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Alert stock (Guest/Visitor)'),
                        'componentType' => 'container',
                        'component' => 'Magento_Ui/js/form/components/html',
                        'additionalClasses' => 'admin__fieldset-note',
                        'content' => '<h4>' . __('Alert Stock (Guest/Visitor)') . '</h4>' .
                            $this->layoutFactory->create()->createBlock(
                                \Eguana\NotifyMe\Block\Adminhtml\Product\Edit\Tab\Alerts\Stock::class
                            )->toHtml(),
                    ],
                ],
            ],
            'children' => [],
        ];
    }

    /**
     * Show Guest Stock Tab
     *
     * @return mixed
     */
    private function canShowTab()
    {
        $alertStockAllowGuest = $this->scopeConfig->getValue(
            'catalog/productalert/allow_stock_guest',
            ScopeInterface::SCOPE_STORE
        );

        return true;//($alertStockAllowGuest);
    }
}
