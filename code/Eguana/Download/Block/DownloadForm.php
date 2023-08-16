<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eguana\Download\Block;

use Magento\Framework\View\Element\Template;

/**
 * Main download form block
 *
 * @api
 * @since 100.0.2
 */
class DownloadForm extends Template
{
    /**
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * Returns action url for download form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('download/index/downloadSuccess');
    }
}
