<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 30/11/21
 * Time: 5:24 PM
 */
namespace Eguana\NotifyMe\Model\ResourceModel\Notification;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Eguana\NotifyMe\Model\Notification as Model;
use Eguana\NotifyMe\Model\ResourceModel\Notification as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'alert_selling_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'eguana_notification_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
