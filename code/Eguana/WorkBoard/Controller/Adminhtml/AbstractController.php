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

namespace Eguana\WorkBoard\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Abstract class for actions
 *
 * Abstract AbstractController
 */
abstract class AbstractController extends Action
{
    public const ADMIN_RESOURCE = 'Eguana_WorkBoard::manage_board';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * This method is used to init breadcrumbs
     *
     * @param mixed $resultPage
     * @return mixed
     */
    public function _init($resultPage)
    {
        $resultPage->setActiveMenu('Eguana_WorkBoard');
        $resultPage->addBreadcrumb(__('Board'), __('Board'));
        $resultPage->addBreadcrumb(__('Manage Board'), __('Manage Board'));
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Board'));

        return $resultPage;
    }
}
