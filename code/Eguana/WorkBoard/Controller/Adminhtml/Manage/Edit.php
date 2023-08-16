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

use Eguana\WorkBoard\Api\BoardRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface as ResponseInterfaceAlias;
use Magento\Framework\Controller\Result\Redirect as RedirectAlias;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface as ResultInterfaceAlias;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

/**
 * This Class is used to add new or update existing record
 *
 * Class Edit
 */
class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Eguana_WorkBoard::manage_board';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var BoardRepositoryInterface
     */
    private BoardRepositoryInterface $boardRepositoryInterface;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * Edit constructor.
     *
     * @param Context $context
     * @param RedirectFactory $redirectFactory
     * @param PageFactory $resultPageFactory
     * @param LoggerInterface $logger
     * @param BoardRepositoryInterface $boardRepositoryInterface
     */
    public function __construct(
        Context $context,
        RedirectFactory $redirectFactory,
        PageFactory $resultPageFactory,
        LoggerInterface $logger,
        BoardRepositoryInterface $boardRepositoryInterface
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->redirectFactory = $redirectFactory;
        $this->logger = $logger;
        $this->boardRepositoryInterface = $boardRepositoryInterface;
        parent::__construct($context);
    }

    /**
     * Edit CMS page
     *
     * @return Page|ResponseInterfaceAlias|RedirectAlias|ResultInterfaceAlias
     */
    public function execute()
    {
        // 1. Get ID and create model and breadcrumbs
        $id = $this->getRequest()->getParam('board_id');
        if (isset($id)) {
            $resultRedirect = $this->resultRedirectFactory->create();
            try {
                $this->boardRepositoryInterface->getById($id);
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        }
        $model = $id ? $this->boardRepositoryInterface->getById($id) : null;
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Eguana_WorkBoard::board')
            ->addBreadcrumb(__('Board'), __('Board'))
            ->addBreadcrumb(__('Manage Board'), __('Manage Board'));
        $resultPage->addBreadcrumb(
            $id ? __('Edit Board') : __('New Board'),
            $id ? __('Edit Board') : __('New Board')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Board'));
        $resultPage->getConfig()->getTitle()
            ->prepend($id ? $model->getTitle() : __('New Board'));
        return $resultPage;
    }
}
