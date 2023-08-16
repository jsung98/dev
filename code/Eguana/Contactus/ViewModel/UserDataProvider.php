<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Eguana\Contactus\ViewModel;

use Magento\Contact\Helper\Data;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Provides the user data to fill the form.
 */
class UserDataProvider extends \Magento\Contact\ViewModel\UserDataProvider
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * UserDataProvider constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    ) {
        $this->helper = $helper;
        parent::__construct($helper);
    }

    /**
     * Get user companyname
     *
     * @return string
     */
    public function getUserCompanyName()
    {
        return $this->helper->getPostValue('companyname');
    }
}
