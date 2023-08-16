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

namespace Eguana\WorkBoard\Model\ResourceModel\Board;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Model\Board as BoardModel;
use Eguana\WorkBoard\Model\ResourceModel\AbstractCollection;
use Eguana\WorkBoard\Model\ResourceModel\Board as BoardResourceModel;
use Magento\Store\Model\Store;

/**
 * collection for Board model & resource model
 *
 * Board Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'board_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Board prefix
     *
     * @var string
     */
    protected $eventPrefix = 'work_board_collection';

    /**
     * Board object
     *
     * @var string
     */
    protected $eventObject = 'board_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            BoardModel::class,
            BoardResourceModel::class
        );
        $this->_map['fields']['store']      = 'store_table.store_id';
        $this->_map['fields']['board_id']   = 'main_table.board_id';
    }

    /**
     * Set first store flag
     *
     * @param bool $flag
     * @return $this
     */
    public function setFirstStoreFlag($flag = false)
    {
        $this->_previewFlag = $flag;
        return $this;
    }

    /**
     * Add filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return $this|mixed
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if (!$this->getFlag('store_filter_added')) {
            $this->performAddStoreFilter($store, $withAdmin);
            $this->setFlag('store_filter_added', true);
        }

        return $this;
    }

    /**
     * Options array method
     *
     * @return array
     */
    public function toOptionArray() : array
    {
        return $this->_toOptionArray('board_id', 'title');
    }

    /**
     * Retrieve all ids for collection
     *
     * Backward compatibility with EAV collection
     *
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function getAllIds($limit = null, $offset = null)
    {
        return $this->getConnection()->fetchCol(
            $this->_getAllIdsSelect($limit, $offset),
            $this->_bindParams
        );
    }

    /**
     * Perform operations after collection load
     *
     * @return Collection
     */
    protected function _afterLoad() : Collection
    {
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
            $this->performAfterLoad('eguana_board_store', $entityMetadata->getLinkField());
            $this->_previewFlag = false;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return parent::_afterLoad();
    }

    /**
     * Perform operations before rendering filters
     *
     * @throws \Exception
     */
    protected function _renderFiltersBefore() : void
    {
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
            $this->joinStoreRelationTable(
                'eguana_board_store',
                $entityMetadata->getLinkField()
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
