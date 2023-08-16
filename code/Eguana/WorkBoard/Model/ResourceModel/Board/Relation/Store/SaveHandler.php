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

namespace Eguana\WorkBoard\Model\ResourceModel\Board\Relation\Store;

use Eguana\WorkBoard\Api\Data\BoardInterface;
use Eguana\WorkBoard\Model\ResourceModel\Board;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Psr\Log\LoggerInterface;

/**
 * This class is used to save store view ids
 *
 * Class SaveHandler
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var Board
     */
    private $resourceEvent;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param MetadataPool $metadataPool
     * @param Board $resourceEvent
     * @param LoggerInterface $logger
     */
    public function __construct(
        MetadataPool $metadataPool,
        Board $resourceEvent,
        LoggerInterface $logger
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceEvent = $resourceEvent;
        $this->logger = $logger;
    }

    /**
     * Execute method
     *
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        $result = '';
        try {
            $entityMetadata = $this->metadataPool->getMetadata(BoardInterface::class);
            $linkField = $entityMetadata->getLinkField();

            $connection = $entityMetadata->getEntityConnection();

            $oldStores = $this->resourceEvent->lookupStoreIds((int)$entity->getId());
            $newStores = (array)$entity->getStores();
            $newCategories = (array)$entity->getData('category');

            $table = $this->resourceEvent->getTable('eguana_board_store');

            $delete = $oldStores;
            if ($delete) {
                $where = [
                    $linkField . ' = ?' => (int)$entity->getData($linkField),
                    'store_id IN (?)' => $delete,
                ];
                $connection->delete($table, $where);
            }
            $insert = $newStores;
            if ($insert) {
                $data = [];
                $index = 0;
                foreach ($insert as $storeId) {
                    $data[] = [
                        $linkField => (int)$entity->getData($linkField),
                        'store_id' => (int)$storeId,
                        'category' => $newCategories[$index],
                    ];
                    $index++;
                }
                $connection->insertMultiple($table, $data);
            }
            return $entity;
        } catch (\Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }
        return $result;
    }
}
