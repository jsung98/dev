<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Theme data helper
 */
namespace Eguana\Theme\Helper;

use Magento\Theme\Block\Html\Title;
use Magento\Framework\View\Element\Template;

class Theme extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $title;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Theme\Block\Html\Title $title
    ) {
        $this->_title = $title;
        parent::__construct($context);
    }

    /**
     * Provide own page title or pick it from Head Block
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->_title->getPageTitle();
    }
}
