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

namespace Eguana\WorkBoard\Model\Board\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class to convert labels on admin panel
 *
 * Class MainActive
 */
class MainActive implements OptionSourceInterface
{
    /**
     * @const STATUS_ENABLED
     */
    public const STATUS_ENABLED = 1;

    /**
     * @const STATUS_DISABLED
     */
    public const STATUS_DISABLED = 0;

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray() : array
    {
        $availableOptions = $this->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }

    /**
     * Get status
     *
     * @return array
     */
    private function getAvailableStatuses() : array
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }
}
