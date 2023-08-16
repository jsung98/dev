<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 7/12/21
 * Time: 7:19 PM
 */

namespace Eguana\Contactus\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{
    const TYPE_DEVELOPMENT = 0;
    const TYPE_MAINTENANCE = 1;
    const TYPE_EXTENSION = 2;
    const TYPE_OTHERS = 3;
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            'label' => __('Development Project'),
            'value' => self::TYPE_DEVELOPMENT
        ];
        $options[] = [
            'label' => __('Maintenance Project'),
            'value' => self::TYPE_MAINTENANCE
        ];
        $options[] = [
            'label' => __('Extension Modules'),
            'value' => self::TYPE_EXTENSION
        ];
        $options[] = [
            'label' => __('Others'),
            'value' => self::TYPE_OTHERS
        ];
        return $options;
    }
    public function getType()
    {
        $options = [
            self::TYPE_DEVELOPMENT => __('Development Project'),
            self::TYPE_MAINTENANCE => __('Maintenance Project'),
            self::TYPE_EXTENSION => __('Extension Modules'),
            self::TYPE_OTHERS => __('Others')
        ];
        return $options;
    }
}
