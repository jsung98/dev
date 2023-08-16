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

namespace Eguana\WorkBoard\Ui\Component\Listing\Column;

use Magento\Store\Ui\Component\Listing\Column\Store\Options;

/**
 * Class StoreOptions
 *
 * Eguana\Faq\Ui\Component\Listing\Column
 */
class StoreOptions extends Options
{
    /**
     * ToOptionArray method
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }
        $this->generateCurrentOptions();
        $this->options = array_values($this->currentOptions);
        return $this->options;
    }
}
