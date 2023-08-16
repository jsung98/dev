<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2023 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: Talish
 * Date: 7/4/23
 * Time: 8:05 PM
 */

namespace Eguana\Contactus\Model\ResourceModel\Contactus\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\LocalizedException;
use Eguana\Contactus\Model\ResourceModel\Contactus;
use Psr\Log\LoggerInterface as Logger;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     * @throws LocalizedException
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'eguana_contact',
        $resourceModel = Contactus::class
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }
    /**
     * @inheritdoc
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        return $this;
    }
    /**
     * @inheritDoc
     */
    public function addFieldToFilter($field, $condition = null)
    {
        return parent::addFieldToFilter($field, $condition);
    }
}
