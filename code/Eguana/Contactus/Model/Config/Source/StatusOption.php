<?php
/**
 * @author Eguana Team
 * @copyriht Copyright (c) 2021 Eguana {http://eguanacommerce.com}
 * Created by PhpStorm
 * User: owais
 * Date: 8/12/21
 * Time: 4:44 PM
 */
namespace Eguana\Contactus\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class StatusOption implements OptionSourceInterface
{
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_APPROVED = 2;

    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            'label' => __('Pending'),
            'value' => self::STATUS_PENDING
        ];
        $options[] = [
            'label' => __('Processing'),
            'value' => self::STATUS_PROCESSING
        ];
        $options[] = [
            'label' => __('Approved'),
            'value' => self::STATUS_APPROVED
        ];
        return $options;
    }

    public function getStatuses()
    {
        $options = [
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_PROCESSING => __('Processing'),
            self::STATUS_APPROVED => __('Approved'),
        ];
        return $options;
    }
}
