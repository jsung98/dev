<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 30/11/21
 * Time: 5:24 PM
 */
namespace Eguana\Contactus\Model\ResourceModel\Contactus;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Eguana\Contactus\Model\Contactus as Model;
use Eguana\Contactus\Model\ResourceModel\Contactus as ResourceModel;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'contact_id';

    /**
     * @var string
     */
    protected $_eventPrefix = 'eguana_contact_collection';

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
