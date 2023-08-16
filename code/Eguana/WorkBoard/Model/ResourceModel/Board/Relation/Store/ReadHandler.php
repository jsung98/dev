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

use Eguana\WorkBoard\Model\ResourceModel\Board;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * This class is used to read stores handle
 *
 * Class ReadHandler
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var Board
     */
    private $resourceEvent;

    /**
     * @param Board $resourceEvent
     */
    public function __construct(
        Board $resourceEvent
    ) {
        $this->resourceEvent = $resourceEvent;
    }

    /**
     * Execute method
     *
     * @param object $entity
     * @param array $arguments
     * @return bool|object
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resourceEvent->lookupStoreIds((int)$entity->getId());
            $categories = $this->resourceEvent->lookCategoryIds((int)$entity->getId());
            $entity->setData('store_id', $stores);
            $entity->setData('category', $categories);
            $entity->setData('stores', $stores);
        }

        return $entity;
    }
}
