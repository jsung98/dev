<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 30/11/21
 * Time: 4:20 PM
 */
namespace Eguana\NotifyMe\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Notification extends AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('eguana_product_alert_selling', 'alert_selling_id');
    }
}
