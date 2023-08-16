<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Eguana\Contactus\Controller\Index;

use Magento\Framework\Controller\ResultFactory;

/**
 * Contact Success controller
 */
class ContactSuccess extends \Magento\Framework\App\Action\Action
{
    /**
     * Show Success Page
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
