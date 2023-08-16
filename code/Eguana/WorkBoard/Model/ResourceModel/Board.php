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

namespace Eguana\WorkBoard\Model\ResourceModel;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * mysql resource class
 *
 * Class Board
 */
class Board extends AbstractDb
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Context $context
     * @param MetadataPool $metadataPool
     * @param EntityManager $entityManager
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     * @param string|int|null $connectionName
     */
    public function __construct(
        Context $context,
        MetadataPool $metadataPool,
        EntityManager $entityManager,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->logger           = $logger;
        $this->metadataPool     = $metadataPool;
        $this->storeManager     = $storeManager;
        $this->entityManager    = $entityManager;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('eguana_board', 'board_id');
    }

    /**
     * Get connection
     *
     * @return false|AdapterInterface
     */
    public function getConnection()
    {
        $connection = '';
        try {
            $connection = $this->metadataPool->getMetadata(BoardInterface::class)
                ->getEntityConnection();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $connection;
    }

    /**
     * Process board data before saving
     *
     * @param AbstractModel $object
     * @return Board
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->isValidEventIdentifier($object)) {
            throw new LocalizedException(
                __(
                    "The Board URL key can't use capital letters or disallowed symbols. "
                    . "Remove the letters and symbols and try again."
                )
            );
        }

        if ($this->isNumericEventIdentifier($object)) {
            throw new LocalizedException(
                __("The Board URL key can't use only numbers. Add letters or words and try again.")
            );
        }
        return parent::_beforeSave($object);
    }

    /**
     * Get Board ID
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string|null $field
     * @return false|int|mixed|string
     */
    private function getEventId(AbstractModel $object, $value, $field = null)
    {
        $boardId = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);

            if (!is_numeric($value) && $field === null) {
                $field = 'board_id';
            } elseif (!$field) {
                $field = $entityMetadata->getIdentifierField();
            }

            $boardId = $value;
            if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
                $select = $this->_getLoadSelect($field, $value, $object);
                $select->reset(Select::COLUMNS)
                    ->columns($this->getMainTable() . 'ResourceModel' . $entityMetadata->getIdentifierField())
                    ->limit(1);
                $result = $this->getConnection()->fetchCol($select);
                $boardId = count($result) ? $result[0] : false;
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $boardId;
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param int|null $field
     * @return $this|Board
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $boardId = $this->getEventId($object, $value, $field);
        if ($boardId) {
            $this->entityManager->load($object, $boardId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param AbstractModel $object
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object) : Select
    {
        $entityMetadata = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $storeIds = [
                Store::DEFAULT_STORE_ID,
                (int)$object->getStoreId(),
            ];

            $select->join(
                ['eers' => $this->getTable('eguana_board_store')],
                $this->getMainTable() . 'ResourceModel' . $linkField . ' = eguana_board_store.' . $linkField,
                []
            )
                ->where('is_active = ?', 1)
                ->where('eers.store_id IN (?)', $storeIds)
                ->limit(1);
        }
        return $select;
    }

    /**
     * Retrieve load select with filter by board id, store and activity
     *
     * @param int $id
     * @param array $store
     * @param int $isActive
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadByIdSelect($id, $store, $isActive = null)
    {
        $entityMetadata = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $linkField = $entityMetadata->getLinkField();

        $select = $this->getConnection()->select()
            ->from(['eer' => $this->getMainTable()])
            ->join(
                ['eers' => $this->getTable('eguana_board_store')],
                'eer.' . $linkField . ' = eers.' . $linkField,
                []
            )
            ->where('eer.board_id = ?', $id)
            ->where('eers.store_id IN (?)', $store);

        if ($isActive !== null) {
            $select->where('eer.is_active = ?', $isActive);
        }

        return $select;
    }

    /**
     *  Check whether notice identifier is numeric
     *
     * @param AbstractModel $object
     * @return bool
     */
    private function isNumericEventIdentifier(AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('identifier'));
    }

    /**
     *  Check whether board identifier is valid
     *
     * @param AbstractModel $object
     * @return bool
     */
    private function isValidEventIdentifier(AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('identifier'));
    }

    /**
     * Check if board id exist for specific store, return board id if board exists
     *
     * @param int $boardId
     * @param int $storeId
     * @return string
     * @throws LocalizedException
     */
    public function checkId($boardId, $storeId) : string
    {
        $entityMetadata = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $stores = [Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->_getLoadByIdSelect($boardId, $stores, 1);
        $select->reset(Select::COLUMNS)
            ->columns('eer.' . $entityMetadata->getIdentifierField())
            ->order('eers.store_id DESC')
            ->limit(1);

        return $this->getConnection()->fetchOne($select);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $boardId
     * @return array
     * @throws LocalizedException
     */
    public function lookupStoreIds($boardId) : array
    {
        $connection = $this->getConnection();
        $entityMetadata = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['eers' => $this->getTable('eguana_board_store')], 'store_id')
            ->join(
                ['eer' => $this->getMainTable()],
                'eers.' . $linkField . ' = eer.' . $linkField,
                []
            )
            ->where('eer.' . $entityMetadata->getIdentifierField() . ' = :board_id');
        return $connection->fetchCol($select, ['board_id' => (int)$boardId]);
    }

    /**
     * Get category ids to which specified item is assigned
     *
     * @param int $boardId
     * @return array
     * @throws LocalizedException
     */
    public function lookCategoryIds($boardId)
    {
        $connection = $this->getConnection();
        $entityMetadata = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['eers' => $this->getTable('eguana_board_store')], 'category')
            ->join(
                ['eer' => $this->getMainTable()],
                'eers.' . $linkField . ' = eer.' . $linkField,
                []
            )
            ->where('eer.' . $entityMetadata->getIdentifierField() . ' = :board_id');
        $categoryString = $connection->fetchOne($select, ['board_id' => (int)$boardId]);

        return explode(',', $categoryString);
    }

    /**
     * Save method
     *
     * @param AbstractModel $object
     * @return array|Board|object
     */
    public function save(AbstractModel $object)
    {
        $return = [];
        try {
            $return = $this->entityManager->save($object);
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }
        return $return;
    }

    /**
     * Delete notice method
     *
     * @param AbstractModel $object
     * @return bool|Board
     */
    public function delete(AbstractModel $object)
    {
        $return = false;
        try {
            $return = $this->entityManager->delete($object);
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }
        return $return;
    }
}
