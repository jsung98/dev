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

namespace Eguana\WorkBoard\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Class Dynamic Category create in configuration
 */
class Category extends AbstractFieldArray
{
    /**
     * Prepare existing row data object
     */
    protected function _prepareToRender()
    {
        $this->addColumn(
            'attribute_name',
            [
                'label' => __('Name'),
                'class' => 'required-entry',
            ]
        );
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
