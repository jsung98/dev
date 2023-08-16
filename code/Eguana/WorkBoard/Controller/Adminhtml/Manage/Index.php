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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\Controller\ResultInterface as ResultInterfaceAlias;
use Magento\Framework\View\Result\Page as PageAlias;
use Magento\Framework\View\Result\PageFactory;

/**
 * This class is used to show the Grid for Board Record in Admin Panel
 *
 * Class Index
 */
class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * This method is used to create new page and add title on the Grid page
     *
     * @return ResponseInterfaceAlias|ResultInterfaceAlias|PageAlias
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('Manage Board')));
        return $resultPage;
    }
}
