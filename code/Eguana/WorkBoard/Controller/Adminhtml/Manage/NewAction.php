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

namespace Eguana\WorkBoard\Controller\Adminhtml\Manage;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface as ResultInterfaceAlias;

/**
 * This redirects to the edit controller
 * Class NewAction
 */
class NewAction implements HttpGetActionInterface
{
    /**
     * @var ForwardFactory
     */
    private ForwardFactory $forwardFactory;

    /**
     * @param ForwardFactory $forwardFactory
     */
    public function __construct(
        ForwardFactory $forwardFactory
    ) {
        $this->forwardFactory = $forwardFactory;
    }
    /**
     * Execute Method
     *
     * This method is used to redirect to Edit Controller
     *
     * @return ResponseInterfaceAlias|ResultInterfaceAlias|void
     */
    public function execute()
    {
        $resultForward = $this->forwardFactory->create();
        return $resultForward->forward('edit');
    }
}
